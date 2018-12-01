<?php
/**
 * Created by PhpStorm.
 * User: el
 * Date: 9/20/18
 * Time: 12:12 PM
 */

namespace App\Domain\Model\price;



class UnitQuantityIntervalDiscountFactor
{


    public function __construct($serviceid, $lowerbound, $upperbound, $reductionfactor)
    {

        if($reductionfactor > 1) throw new \InvalidArgumentException("The reduction factor must not be > 1");
        if($lowerbound > $upperbound) throw new \InvalidArgumentException("The lower bound must be inferior to the lower bound");

        $this->serviceid = $serviceid;
        $this->lowerbound = $lowerbound;
        $this->upperbound = $upperbound;
        $this->reductionfactor = $reductionfactor;

    }


}