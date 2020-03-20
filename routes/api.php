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
    Route::get('province', ['as' => 'shipping_fee.province', 'uses' => 'ShippingFeeController@provinces']);

    //Shipping province
    Route::get('province/{province_id}/city', ['as' => 'shipping_fee.province', 'uses' => 'ShippingFeeController@cities']);

    //Shipping quotation
    Route::post('quotation', ['as' => 'shipping_fee.quotation', 'uses' => 'ShippingFeeController@quotation']);
});

//FAQs
Route::group(['prefix' => 'faq'], function () {
    //FAQ Index
    Route::get('/', ['as' => 'faq.index', 'uses' => 'FaqController@index']);

    //FAQ Show
    Route::get('{faq_id}', ['as' => 'faq.show', 'uses' => 'FaqController@show']);
});

//PROMOTIONS
Route::group(['prefix' => 'promotion'], function () {
    //Promotion Index
    Route::get('/', ['as' => 'promotion.index', 'uses' => 'PromotionController@index']);
});

//WHEN LOGGED IN
Route::group(['middleware' => 'auth:api'], function () {

    //USER
    Route::group(['prefix' => 'user'], function () {
        //User Show
        Route::get('show', ['as' => 'user.show', 'uses' => 'UserController@show']);

        //User Edit
        Route::get('edit', ['as' => 'user.edit', 'uses' => 'UserController@edit']);

        //User Update
        Route::post('update', ['as' => 'user.update', 'uses' => 'UserController@update']);
    });

    //CONFIGURATION
    Route::group(['prefix' => 'configuration'], function () {
        //Configuration Index
        Route::get('/', ['as' => 'configuration.index', 'uses' => 'ConfigurationController@index']);

        //Configuration Bank Information
        Route::get('bank', ['as' => 'configuration.bank', 'uses' => 'ConfigurationController@bank']);

        //Configuration GCash
        Route::get('gcash', ['as' => 'configuration.gcash', 'uses' => 'ConfigurationController@gcash']);

        //Configuration Contact
        Route::get('contact', ['as' => 'configuration.contact', 'uses' => 'ConfigurationController@contact']);
    });

    //PRODUCTS
    Route::group(['prefix' => 'product'], function () {
        //Browse Products
        Route::get('/', ['as' => 'product.index', 'uses' => 'ProductController@index']);

        //Best Seller Products
        Route::get('seller', ['as' => 'product.seller', 'uses' => 'ProductController@bestSeller']);

        //Best Seller Products
        Route::get('newest', ['as' => 'product.newest', 'uses' => 'ProductController@newestProduct']);

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

        //Show Orders
        Route::get('show/{order_code}', ['as' => 'order.show', 'uses' => 'OrderController@show']);

        //Cancel Order
        Route::get('create', ['as' => 'order.create', 'uses' => 'OrderController@create']);

        //Store Order
        Route::post('/', ['as' => 'order.store', 'uses' => 'OrderController@store']);

        //Update Order
        Route::post('update/{order_code}', ['as' => 'order.update', 'uses' => 'OrderController@update']);

        //Cancel Order
        Route::get('cancel/{order_code}', ['as' => 'order.cancel', 'uses' => 'OrderController@cancel']);

        //Cancel Summary
        Route::post('summary', ['as' => 'order.summary', 'uses' => 'OrderController@summary']);
    });
});

//Product Photo
Route::get('image/{image_name}', ['as' => 'image.api', 'uses' => 'PhotoController@show']);

//Promotion Photo
Route::get('image/promotion/{image_name}', ['as' => 'image.promotion', 'uses' => 'PhotoController@promotionImage']);
