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

Route::group(['middleware' => 'auth:api'], function() {
    Route::get('user', ['as' => 'user', 'uses' => 'UserController@user']);
    Route::get('test', ['as' => 'test', 'uses' => 'UserController@test']);

    //PRODUCTS
    Route::group(['prefix' => 'product'], function() {
        //Browse Products
        Route::get('browse', ['as' => 'product.index', 'uses' => 'ProductController@index']);

        //Show Product
        Route::get('{code}', ['as' => 'product.show', 'uses' => 'ProductController@show']);
    });

    //CART
    Route::group(['prefix' => 'cart'], function() {
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
});