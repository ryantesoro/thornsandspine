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

Route::post('login', ['as' => 'login', 'uses' => 'UserController@login']);
Route::post('register', ['as' => 'register', 'uses' => 'UserController@register']);

Route::post('email/verify', ['as' => 'email.verify', 'uses' => 'VerificationController@verify']);
Route::post('email/resend', ['as' => 'email.resend', 'uses' => 'VerificationController@resend']);

Route::post('password/request', ['as' => 'password.request', 'uses' => 'ResetPasswordController@req']);
Route::post('password/verify', ['as' => 'password.verify', 'uses' => 'ResetPasswordController@verify']);
Route::post('password/reset', ['as' => 'password.reset', 'uses' => 'ResetPasswordController@reset']);

//SHIPPING FEES
Route::group(['prefix' => 'shipping'], function () { 
    //Shipping province
    Route::get('province', ['as' => 'shipping_fee.province', 'uses' => 'ShippingFeeController@province']);

    //Shipping province
    Route::get('province/{province_id}/city', ['as' => 'shipping_fee.province', 'uses' => 'ShippingFeeController@city']);
});

//WHEN LOGGED IN
Route::group(['middleware' => 'auth:api'], function () {

    //PRODUCTS
    Route::group(['prefix' => 'product'], function () {
        //Browse Products
        Route::get('/', ['as' => 'product.index', 'uses' => 'ProductController@index']);

        //Search Products
        Route::get('search', ['as' => 'product.show', 'uses' => 'ProductController@search']);

        //Show Product
        Route::get('{code}', ['as' => 'product.show', 'uses' => 'ProductController@show']);
    });

    //CART
    Route::group(['prefix' => 'cart'], function () {
        //Browse Cart
        Route::get('/', ['as' => 'cart.index', 'uses' => 'CartController@index']);

        //Store Cart
        Route::post('/', ['as' => 'cart.store', 'uses' => 'CartController@store']);

        //Update Cart
        Route::post('update/{cart_id}', ['as' => 'cart.update', 'uses' => 'CartController@update']);

        //Delete Cart
        Route::post('delete/{cart_id}', ['as' => 'cart.destroy', 'uses' => 'CartController@destroy']);

        //Delete Cart
        Route::get('clear', ['as' => 'cart.clear', 'uses' => 'CartController@clear']);
    });

    //ORDERS
    Route::group(['prefix' => 'order'], function () {
        //Browse Orders
        Route::get('/', ['as' => 'order.index', 'uses' => 'OrderController@index']);

        //Store Order
        Route::post('/', ['as' => 'order.store', 'uses' => 'OrderController@store']);
    });
});

//Product Photo
Route::get('image/{image_name}', ['as' => 'image.api', 'uses' => 'PhotoController@show']);
