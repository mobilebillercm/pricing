<?php

namespace App\Domain\Model\price;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{

    protected $table = 'currencies';
    protected $fillable = ['currencyid', 'name'];


    public function __construct($currencyid = null, $name =null, $attributes = array())
    {
        parent::__construct($attributes);
        $this->currencyid = $currencyid;
        $this->name = $name;
    }

}
