<?php
/**
 * Created by PhpStorm.
 * User: el
 * Date: 9/18/18
 * Time: 10:07 AM
 */

namespace App\Domain\Model\service;


use Illuminate\Database\Eloquent\Model;

class ServiceWithNoUnitPriceAssigned extends Model
{



    protected $table = 'service_with_no_unit_price_assigned';
    protected $fillable = ['service_id', 'name', 'description'];



    public function __construct($service_id = null, $name = null, $description = null, $attributes = array())
    {
        parent::__construct($attributes);
        $this->service_id = $service_id;
        $this->name = $name;
        $this->description = $description;
    }


}