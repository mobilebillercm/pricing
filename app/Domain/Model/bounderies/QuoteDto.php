<?php
/**
 * Created by PhpStorm.
 * User: el
 * Date: 9/18/18
 * Time: 9:55 AM
 */

namespace App\Domain\Model\service;


use App\Domain\Model\Currency;
use App\Domain\Model\price\UnitQuantity;
use App\Domain\Model\Unit;

class QuoteDto
{


    public $serviceid;
    public $tenantid;
    public $currency;
    public $unit;
    public $unitQuantity;


    public function __construct(Currency $currency, Unit $unit, UnitQuantity $unitQuantity)
    {
        $this->currency = $currency;
        $this-> unit  = $unit;
        $this->unitQuantity = $unitQuantity;

    }

}