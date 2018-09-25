<?php
/**
 * Created by PhpStorm.
 * User: el
 * Date: 9/18/18
 * Time: 10:07 AM
 */

namespace App\Domain\Model\service;



use Illuminate\Database\Eloquent\Model;

class ServiceWithUnitPriceAssigned extends Model
{



    protected $table = 'service_with_unit_price_assigned';
    protected $fillable = ['serviceid', 'name', 'description', 'unit', 'amount', 'currency', 'unitquantityintervaldiscountsactors'];


    public function __construct($serviceid = null, $name = null, $description = null, $unit = null, $amount = null,
                                $currency = null, $unitquantityintervaldiscountsactors = null, $attributes = array())
    {

        parent::__construct($attributes);
        $this->serviceid = $serviceid;
        $this->name = $name;
        $this->description = $description;
        $this->unit = $unit;
        $this->amount = $amount;
        $this->currency = $currency;
        $this->unitquantityintervaldiscountsactors = $unitquantityintervaldiscountsactors;


    }


}