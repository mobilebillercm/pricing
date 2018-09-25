<?php
/**
 * Created by PhpStorm.
 * User: el
 * Date: 9/20/18
 * Time: 12:07 PM
 */

namespace App\Domain\service;



use App\Domain\GlobalResultHandler;
use App\Domain\Model\price\Amount;
use App\Domain\Model\price\Currency;
use App\Domain\Model\price\Price;
use App\Domain\Model\price\UnitQuantity;
use App\Domain\Model\service\ServiceWithUnitPriceAssigned;

class PriceCalculationService
{

    public static function calculateBasicServicePrice(ServiceWithUnitPriceAssigned  $service, UnitQuantity $unitQuantity, $allUnitQuantityIntervalDiscountFactors){

        $serviceUnitPrice = $service->amount;
        $serviceUnitCurrency = $service->currency;
        $serviceUnitQuantity = $unitQuantity->quantity;



        for($i = 0; $i < count($allUnitQuantityIntervalDiscountFactors); $i++){

            if( $serviceUnitQuantity >= $allUnitQuantityIntervalDiscountFactors[$i]->lowerbound and $serviceUnitQuantity < $allUnitQuantityIntervalDiscountFactors[$i]->upperbound){

                $unitQuantityIntervalDiscountFactor =  $allUnitQuantityIntervalDiscountFactors[$i];

                $serviceTotalPrice = $serviceUnitPrice * $serviceUnitQuantity * (1 - $unitQuantityIntervalDiscountFactor->reductionfactor);

                return response(GlobalResultHandler::buildSuccesResponseArray(new Price(new Amount($serviceTotalPrice), new Currency('',$serviceUnitCurrency))));

            }
        }

        $serviceTotalPrice = $serviceUnitPrice * $serviceUnitQuantity * (1 - 0);

        return response(GlobalResultHandler::buildSuccesResponseArray(new Price(new Amount($serviceTotalPrice), new Currency('',$serviceUnitCurrency))));


    }
}