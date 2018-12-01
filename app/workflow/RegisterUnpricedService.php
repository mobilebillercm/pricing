<?php
/**
 * Created by PhpStorm.
 * User: el
 * Date: 9/20/18
 * Time: 9:23 AM
 */

namespace App\workflow;


use App\database\InsertOneRecord;
use App\Domain\Model\bounderies\UnValidatedUnpricedService;
use App\Domain\Model\price\ServiceWithNoUnitPriceAssigned;
use Ramsey\Uuid\Uuid;

class RegisterUnpricedService
{
    public static function registerUnpricedService(UnValidatedUnpricedService $unValidatedUnpricedService, InsertOneRecord $insertOneRecord){

        $validatedUnpricedService = new ServiceWithNoUnitPriceAssigned(
                                                                        $unValidatedUnpricedService->serviceid,
                                                                        $unValidatedUnpricedService->name,
                                                                        $unValidatedUnpricedService->description);






    }











}