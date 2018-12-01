<?php
/**
 * Created by PhpStorm.
 * User: el
 * Date: 9/21/18
 * Time: 10:16 AM
 */

namespace App\Domain\service;


class UnitQuantityIntervalDiscountFactorValidatorService
{

    public static function validateUnitQuantityIntervalDiscountFactor(array  $unitQuantityIntervalDiscountFactors){

        if(count($unitQuantityIntervalDiscountFactors) === 0){
            return true;
        }else{

            for ($i = 0; $i < count($unitQuantityIntervalDiscountFactors); $i++){

                if($i-1 >= 0) {

                    if(($unitQuantityIntervalDiscountFactors[$i-1]->upperbound - $unitQuantityIntervalDiscountFactors[$i]->lowerbound) != 0) return false;
                }

            }


            return true;
        }
    }

}