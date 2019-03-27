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


Route::group(['prefix' => 'v1', 'as' => 'api.v1.'], function ()
{
    //1
    Route::get('auth/register', 'AuthController@registrationUser');
    //2
    Route::get('auth/login', 'AuthController@loginApi');

    Route::group(['middleware' => ['auth:api', 'admin_and_customer']], function () {
        //3
        Route::post('cart/items/{item}', 'ManagerController@addItem');
        //4
        Route::delete('cart/item/{item}', 'ManagerController@delItem');
        //5
        Route::post('cart/item/checkout', 'ManagerController@makeOrder');
        //6
        Route::get('me/info', 'ManagerController@getMeInfo');
        //7
        Route::get('me/orders', 'ManagerController@getMeOrders');
    });

    Route::group(['middleware' => ['auth:api', 'store_user_and_admin']], function () {
        //8
        Route::post('store/{store}/items', 'ManagerController@addItemAndArrIngredients');
        //9


    });




});