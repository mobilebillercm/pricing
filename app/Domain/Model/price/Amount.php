<?php
/**
 * Created by PhpStorm.
 * User: el
 * Date: 9/17/18
 * Time: 2:00 PM
 */

namespace App\Domain\Model\price;


class Amount
{
    public $value;

    public function __construct($value)
    {
        $this->value = $value;
    }
}