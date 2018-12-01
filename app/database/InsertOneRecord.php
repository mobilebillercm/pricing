<?php
/**
 * Created by PhpStorm.
 * User: el
 * Date: 9/20/18
 * Time: 9:42 AM
 */

namespace App\database;


use Illuminate\Support\Facades\DB;

class InsertOneRecord
{
    public static function  inserOneRecord(DB $db, $connectionname, $tablename, Array $array){

        $db::connection($connectionname)->table($tablename)->insert($array);

    }
}