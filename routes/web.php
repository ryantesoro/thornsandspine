<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Login Page
Route::get('/', ['as' => 'login', 'uses' => 'HomeController@login']);

//Login Process
Route::post('login', ['as' => 'admin.login', 'uses' => 'UserController@login']);

//When Logged In
Route::group(['middleware' => 'auth'], function () {

    //Dashboard Page
    Route::get('dashboard', ['as' => 'admin.dashboard', 'uses' => 'HomeController@dashboard']);

    //PRODUCTS
    Route::group(['prefix' => 'products'], function () {

        //Products Index
        Route::get('/', ['as' => 'admin.product.index', 'uses' => 'ProductController@index']);

        //Product Show
        Route::get('show/{code}', ['as' => 'admin.product.show', 'uses' => 'ProductController@show']);

        //Product Create
        Route::get('create', ['as' => 'admin.product.create', 'uses' => 'ProductController@create']);

        //Product Store
        Route::post('/', ['as' => 'admin.product.store', 'uses' => 'ProductController@store']);

        //Product Edit
        Route::get('edit/{code}', ['as' => 'admin.product.edit', 'uses' => 'ProductController@edit']);

        //Product Update
        Route::post('edit/{product_id}', ['as' => 'admin.product.update', 'uses' => 'ProductController@update']);

        //Product Delete
        Route::post('delete/{product_id}', ['as' => 'admin.product.destroy', 'uses' => 'ProductController@destroy']);

        //Product Restore
        Route::post('restore/{product_id}', ['as' => 'admin.product.restore', 'uses' => 'ProductController@restore']);
    });

    //POTS
    Route::group(['prefix' => 'pots'], function () {

        //Pot Index
        Route::get('/', ['as' => 'admin.pot.index', 'uses' => 'PotController@index']);

        //Pot Create
        Route::get('create', ['as' => 'admin.pot.create', 'uses' => 'PotController@create']);

        //Pot Store
        Route::post('/', ['as' => 'admin.pot.store', 'uses' => 'PotController@store']);

        //Pot Show
        Route::get('show/{pot_id}', ['as' => 'admin.pot.show', 'uses' => 'PotController@show']);

        //Pot Edit
        Route::get('edit/{pot_id}', ['as' => 'admin.pot.edit', 'uses' => 'PotController@edit']);

        //Pot Update
        Route::post('update/{pot_id}', ['as' => 'admin.pot.update', 'uses' => 'PotController@update']);

        //Pot Delete
        Route::post('delete/{pot_id}', ['as' => 'admin.pot.destroy', 'uses' => 'PotController@destroy']);

        //Pot Delete
        Route::post('restore/{pot_id}', ['as' => 'admin.pot.restore', 'uses' => 'PotController@restore']);
    });

    //SHIPPING FEES
    Route::group(['prefix' => 'shipping_fees'], function () {

        //Shipping Fee Index
        Route::get('/', ['as' => 'admin.shipping_fee.index', 'uses' => 'ShippingFeeController@index']);

        //Shipping Fee Show
        Route::get('view/{shipping_fee_id}', ['as' => 'admin.shipping_fee.show', 'uses' => 'ShippingFeeController@show']);

        //Shipping Fee Create
        Route::get('create', ['as' => 'admin.shipping_fee.create', 'uses' => 'ShippingFeeController@create']);

        //Shipping Fee Store
        Route::post('/', ['as' => 'admin.shipping_fee.store', 'uses' => 'ShippingFeeController@store']);

        //Shipping Fee Edit
        Route::get('edit/{shipping_fee_id}', ['as' => 'admin.shipping_fee.edit', 'uses' => 'ShippingFeeController@edit']);

        //Shipping Fee Update
        Route::post('update/{shipping_fee_id}', ['as' => 'admin.shipping_fee.update', 'uses' => 'ShippingFeeController@update']);
    });

    //SHIPPING PROVINCES
    Route::group(['prefix' => 'shipping_province'], function () {

        //Shipping Province Index
        Route::get('/', ['as' => 'admin.shipping_province.index', 'uses' => 'ShippingProvinceController@index']);

        //Shipping Province Create
        Route::get('show/{province_id}', ['as' => 'admin.shipping_province.show', 'uses' => 'ShippingProvinceController@show']);

        //Shipping Province Create
        Route::get('create', ['as' => 'admin.shipping_province.create', 'uses' => 'ShippingProvinceController@create']);

        //Shipping Province Store
        Route::post('/', ['as' => 'admin.shipping_province.store', 'uses' => 'ShippingProvinceController@store']);

        //Shipping Province Edit
        Route::get('edit/{province_id}', ['as' => 'admin.shipping_province.edit', 'uses' => 'ShippingProvinceController@edit']);

        //Shipping Province Update
        Route::post('update/{province_id}', ['as' => 'admin.shipping_province.update', 'uses' => 'ShippingProvinceController@update']);
    });

    //Orders Page
    Route::get('orders', ['as' => 'admin.order.index', 'uses' => 'OrderController@index']);

    //Customers Page
    Route::get('customers', ['as' => 'admin.customer.index', 'uses' => 'CustomerController@index']);

    //Sales Page
    Route::get('sales', ['as' => 'admin.sales.index', 'uses' => 'SalesController@index']);

    //Reports Page
    Route::get('reports', ['as' => 'admin.report.index', 'uses' => 'ReportController@index']);

    //Sign Out
    Route::get('signout', ['as' => 'admin.signout', 'uses' => 'UserController@sign_out']);
});

//Product Photo
Route::get('image/{image_name}', ['as' => 'image', 'uses' => 'PhotoController@show']);
