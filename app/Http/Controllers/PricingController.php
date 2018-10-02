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
use App\Domain\Model\price\UnitQuantity;
use App\Domain\Model\price\UnitQuantityIntervalDiscountFactor;
use App\Domain\Model\service\ServiceWithUnitPriceAssigned;
use App\Domain\service\PriceCalculationService;
use Illuminate\Http\Request;

class PricingController extends  Controller
{

    public function calculateBasicServicePrice(Request $request){


        $validationrules = [

            'serviceid' => GlobalDtoValidator::requireStringMinMax(1, 150),
            //'tenantid' => GlobalDtoValidator::requireStringMinMax(1, 150),
            'unitquantity' => GlobalDtoValidator::requireNumeric()
        ];

        $validator = GlobalDtoValidator::validateData($request->all(), $validationrules) ;


        if ($validator->fails()) {return response(GlobalResultHandler::buildFaillureReasonArray($validator->errors()->first()), 200);}



        $serviceToPrices = ServiceWithUnitPriceAssigned::where('serviceid', '=', $request->get('serviceid'))->get();

        $unitQuantityIntervalDiscountFactors = json_decode($serviceToPrices[0]->unitquantityintervaldiscountsactors);


        if(!(GlobalDbRecordCounter::countDbRecordIsExactlelOne($serviceToPrices)) or !(GlobalDbRecordCounter::countDbRecordIsMultipleOrOne($unitQuantityIntervalDiscountFactors)) ){

            return response(GlobalResultHandler::buildFaillureReasonArray("Invalid data provided"), 200);
        }
        else{

            $serviceToPrice = $serviceToPrices[0];
        }


        return PriceCalculationService::calculateBasicServicePrice($serviceToPrice, new UnitQuantity($request->get('unitquantity')), $unitQuantityIntervalDiscountFactors);

    }


}