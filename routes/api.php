<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/











///API ROUTES
Route::post('/unpriced-services', 'ServiceController@registerUnpricedServiceFromServicesContext');



///CONTEXT ROUTES
Route::post('/define-price-for-unpriced-servivices', 'ServiceController@defineUnitPriceForUnPriceService');

Route::post('/calculate-paid-service-price', 'PricingController@calculateBasicServicePrice');






















Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
