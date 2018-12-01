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
Route::post('/unpriced-services', 'ApiController@registerUnpricedServiceFromServicesContext')->middleware('rabbitmq.client');



///CONTEXT ROUTES
Route::post('/define-price-for-unpriced-services', 'ServiceController@defineUnitPriceForUnPriceService')->middleware('token.verification');

Route::get('/calculate-paid-service-price/{serviceid}/{quantity}', 'PricingController@calculateBasicServicePrice');






















Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
