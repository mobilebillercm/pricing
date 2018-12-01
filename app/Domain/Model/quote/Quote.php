<?php
/**
 * Created by PhpStorm.
 * User: el
 * Date: 9/18/18
 * Time: 9:56 AM
 */

namespace App\Domain\Model\service;


use App\Domain\Model\price\Amount;
use App\Domain\Model\price\Currency;
use App\Domain\Model\price\Price;
use App\Domain\Model\unit\Unit;
use Illuminate\Database\Eloquent\Model;

class Quote extends  Model
{

    protected $table = 'quotes';
    protected $fillable = ['serviceid', 'tenantid', 'name', 'description', 'currency', 'amount', 'unit', 'unitquantity'];


    public function __construct($serviceid = null, $tenantid = null, $name = null, $description = null, $currency = null, $amount = null,  $unit = null, $unitquantity = null, $attributes = array())
    {

        parent::__construct($attributes);
        $this->serviceid = $serviceid;
        $this->tenantid = $tenantid;
        $this->name = $name;
        $this->description = $description;
        $this->currency = $currency;
        $this->amount = $amount;
        $this->unit = $unit;
        $this->unitquantity = $unitquantity;

        $this->unit = new Unit($this->unit);
        $this->price = new Price(new Amount($this->amount), new Currency($this->currency));
    }

}