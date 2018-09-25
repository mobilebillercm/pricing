<?php
/**
 * Created by PhpStorm.
 * User: el
 * Date: 9/17/18
 * Time: 1:44 PM
 */

namespace App\Domain\Model\price;


use http\Exception\InvalidArgumentException;

class UnitQuantity
{
    public $quantity;

    public function __construct(float $quantity)
    {
        if($quantity < 0.0)  throw new InvalidArgumentException("Qunatity must be > 0");
        $this->quantity = $quantity;
    }

}