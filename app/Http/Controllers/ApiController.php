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
use App\Jobs\ProccessMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiController extends  Controller
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

}