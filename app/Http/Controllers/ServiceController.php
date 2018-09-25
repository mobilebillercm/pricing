<?php
/**
 * Created by PhpStorm.
 * User: el
 * Date: 9/20/18
 * Time: 9:21 AM
 */

namespace App\Http\Controllers;


use App\Domain\GlobalDbRecordCounter;
use App\Domain\GlobalDtoValidator;
use App\Domain\GlobalResultHandler;
use App\Domain\Model\price\Currency;
use App\Domain\Model\price\UnitQuantityIntervalDiscountFactor;
use App\Domain\Model\service\ServiceWithNoUnitPriceAssigned;
use App\Domain\Model\service\ServiceWithUnitPriceAssigned;
use App\Domain\Model\unit\Unit;
use App\Domain\service\UnitQuantityIntervalDiscountFactorValidatorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServiceController extends  Controller
{

    public function registerUnpricedServiceFromServicesContext(){


        $dataJson = file_get_contents('php://input');
        $dataArray =  json_decode($dataJson, true);

        if(!$dataArray){
            return response(GlobalResultHandler::buildFaillureReasonArray("Invalid Data"), 200);
        }

        $validationrules =  [
            'serviceid' => GlobalDtoValidator::requireStringMinMax(1, 150),
            'name' => GlobalDtoValidator::requireStringMinMax(1, 150),
            'description' => GlobalDtoValidator::requireStringMinMax(1, 100),
        ];

        $validator = GlobalDtoValidator::validateData($dataArray, $validationrules) ;


        if ($validator->fails()) {

            return response(GlobalResultHandler::buildFaillureReasonArray($validator->errors()->first()), 200);

        }

        $unpricedServiceToRegister = new ServiceWithNoUnitPriceAssigned(
            $dataArray['serviceid'],
            $dataArray['name'],
            $dataArray['description']
        );



        DB::beginTransaction();

        try{

            $unpricedServiceToRegister->save();

        }catch (\Exception $e){

            DB::rollBack();
            return response(GlobalResultHandler::buildFaillureReasonArray('Unable to register Unpriced Service'), 200);
        }

        DB::commit();

        return response(GlobalResultHandler::buildSuccesResponseArray('Unpriced Service Registered Successfully'), 200);


    }


    public function defineUnitPriceForUnPriceService(Request $request){



        $validationrules = [
            'serviceid' => GlobalDtoValidator::requireStringMinMax(1, 150),
            'unitname' => GlobalDtoValidator::requireStringMinMax(1, 10),
            'unitamount' => GlobalDtoValidator::requireNumeric(),
            'currencyid' => GlobalDtoValidator::requireStringMinMax(1, 150)
        ];


        $unitQuantityIntervalDiscountFactorValidationRules = [
            'lowerbound' => GlobalDtoValidator::requireInteger(),
            'upperbound' => GlobalDtoValidator::requireInteger(),
            'reductionfactor' => GlobalDtoValidator::requireNumeric(),
        ];



        DB::beginTransaction();

        try{

        $unitQuantityIntervalDiscountFactor = [];


        for ($j = 0; $request->get('reductionfactor' . $j); $j++) {

            $lowerbound_j = $request->get('lowerbound' . $j);
            $upperbound_j = $request->get('upperbound' . $j);
            $reductionfactor_j = $request->get('reductionfactor' . $j);

            $unitQuantityIntervalDiscountFactorValidator = GlobalDtoValidator::validateData(
                array('lowerbound'=>$lowerbound_j, 'upperbound'=>$upperbound_j, 'reductionfactor'=>$reductionfactor_j),
                $unitQuantityIntervalDiscountFactorValidationRules) ;


            if ($unitQuantityIntervalDiscountFactorValidator->fails()) {return response(GlobalResultHandler::buildFaillureReasonArray($unitQuantityIntervalDiscountFactorValidator->errors()->first()), 200);}

            else{

                array_push($unitQuantityIntervalDiscountFactor, new UnitQuantityIntervalDiscountFactor(
                    $request->get('serviceid'), $lowerbound_j, $upperbound_j, $reductionfactor_j));
            }


        }

        if(!UnitQuantityIntervalDiscountFactorValidatorService::validateUnitQuantityIntervalDiscountFactor($unitQuantityIntervalDiscountFactor)) {

            return response(GlobalResultHandler::buildSuccesResponseArray("Unvalid Unit Quantity Interval Discount Factors") ,200);
        }




        $validator = GlobalDtoValidator::validateData($request->all(), $validationrules) ;



        if ($validator->fails()) {return response(GlobalResultHandler::buildFaillureReasonArray($validator->errors()->first()), 200);}


        $checkThereisACorrespondantUnpricedServices = ServiceWithNoUnitPriceAssigned::where('service_id', '=', $request->get('serviceid'))->get();
        $checkThereisACorrespondantUnits = Unit::where('name', '=', $request->get('unitname'))->get();
        $checkThereisACorrespondantCurrencies = Currency::where('currencyid', '=', $request->get('currencyid'))->get();



        if( (!GlobalDbRecordCounter::countDbRecordIsExactlelOne($checkThereisACorrespondantUnpricedServices)) or
            (!GlobalDbRecordCounter::countDbRecordIsExactlelOne($checkThereisACorrespondantUnits)) or
            (!GlobalDbRecordCounter::countDbRecordIsExactlelOne($checkThereisACorrespondantCurrencies))
        ){

            if(!GlobalDbRecordCounter::countDbRecordIsExactlelOne($checkThereisACorrespondantUnpricedServices)) {
                return response(GlobalResultHandler::buildFaillureReasonArray("Unpriced Service not found"), 200);
            }elseif (!GlobalDbRecordCounter::countDbRecordIsExactlelOne($checkThereisACorrespondantUnits)){
                return response(GlobalResultHandler::buildFaillureReasonArray("Unit not found"), 200);
            }elseif (!GlobalDbRecordCounter::countDbRecordIsExactlelOne($checkThereisACorrespondantCurrencies)){
                return response(GlobalResultHandler::buildFaillureReasonArray("Currency not found"), 200);
            }else{
                return response(GlobalResultHandler::buildFaillureReasonArray("Wrong data provided"), 200);

            }


        }else{

            $checkThereisACorrespondantUnpricedService = $checkThereisACorrespondantUnpricedServices[0];
            $checkThereisACorrespondantUnit = $checkThereisACorrespondantUnits[0];
            $checkThereisACorrespondantCurrency = $checkThereisACorrespondantCurrencies[0];
        }


        $serviceWithUnitPriceToRegister = new ServiceWithUnitPriceAssigned( $checkThereisACorrespondantUnpricedService->service_id,$checkThereisACorrespondantUnpricedService->name,
                                                                            $checkThereisACorrespondantUnpricedService->description, $checkThereisACorrespondantUnit->name,
                                                                            $request->get('unitamount'), $checkThereisACorrespondantCurrency->name,
                                                                            json_encode($unitQuantityIntervalDiscountFactor,JSON_UNESCAPED_SLASHES));






            $checkThereisACorrespondantUnpricedService->delete();
            $serviceWithUnitPriceToRegister->save();

        }catch (\Exception $e){

            DB::rollBack();

            return response(GlobalResultHandler::buildFaillureReasonArray($e->getMessage()), 200);

        }

        DB::commit();


        return response(GlobalResultHandler::buildSuccesResponseArray("Service Unit Price defined successfully"), 200);


    }




}