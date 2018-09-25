<?php
/**
 * Created by PhpStorm.
 * User: el
 * Date: 9/17/18
 * Time: 1:50 PM
 */

namespace App\Domain\Model\price;



class Price
{

    public $amount;
    public $currency;

    public function __construct(Amount $amount , Currency $currency)
    {
        $this->amount = $amount;
        $this->currency = $currency;

    }



}