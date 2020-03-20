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

Route::get('test', ['uses' => 'OrderController@test']);

//When Logged In 
Route::group(['middleware' => 'auth'], function () {

    //SUPER ADMIN
    Route::group(['middleware' => 'super_admin'], function () {
        //CONFIGURATIONS
        Route::group(['prefix' => 'configuration'], function () {
            //Configuration Index
            Route::get('/', ['as' => 'admin.config.index', 'uses' => 'ConfigurationController@index']);

            //Configuration Index
            Route::post('/', ['as' => 'admin.config.update', 'uses' => 'ConfigurationController@update']);
        });

        //LOGS 
        Route::group(['prefix' => 'logs'], function () {

            //Products Index
            Route::get('/', ['as' => 'admin.log.index', 'uses' => 'LogController@index']);
        });

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

            //Product Sales
            Route::get('sales', ['as' => 'admin.sales.product', 'uses' => 'ProductSalesController@index']);

            //Product Sales
            Route::get('sales/print', ['as' => 'admin.sales.product.print', 'uses' => 'ProductSalesController@print']);
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

        //CITIES
        Route::group(['prefix' => 'cities'], function () {

            //City Index
            Route::get('/', ['as' => 'admin.city.index', 'uses' => 'CityController@index']);

            //City Create
            Route::get('create', ['as' => 'admin.city.create', 'uses' => 'CityController@create']);

            //City Store
            Route::post('/', ['as' => 'admin.city.store', 'uses' => 'CityController@store']);

            //City Edit
            Route::get('edit/{city_id}', ['as' => 'admin.city.edit', 'uses' => 'CityController@edit']);

            //City Update
            Route::post('update/{city_id}', ['as' => 'admin.city.update', 'uses' => 'CityController@update']);
        });

        //PROVINCES
        Route::group(['prefix' => 'provinces'], function () {

            //Province Index
            Route::get('/', ['as' => 'admin.province.index', 'uses' => 'ProvinceController@index']);

            //Province Create
            Route::get('create', ['as' => 'admin.province.create', 'uses' => 'ProvinceController@create']);

            //Province Store
            Route::post('/', ['as' => 'admin.province.store', 'uses' => 'ProvinceController@store']);

            //Province Edit
            Route::get('edit/{province_id}', ['as' => 'admin.province.edit', 'uses' => 'ProvinceController@edit']);

            //Province Update
            Route::post('update/{province_id}', ['as' => 'admin.province.update', 'uses' => 'ProvinceController@update']);
        });

        //SHIPPING FEES
        Route::group(['prefix' => 'shipping_fees'], function () {
            //Shipping Fee Create
            Route::get('create', ['as' => 'admin.shipping_fee.create', 'uses' => 'ShippingFeeController@create']);
        });

        //FAQs
        Route::group(['prefix' => 'faq'], function () {
            //Faq Index
            Route::get('/', ['as' => 'admin.faq.index', 'uses' => 'FaqController@index']);

            //Faq Create
            Route::get('create', ['as' => 'admin.faq.create', 'uses' => 'FaqController@create']);

            //Faq Store
            Route::post('store', ['as' => 'admin.faq.store', 'uses' => 'FaqController@store']);

            //Faq Edit
            Route::get('edit/{faq_id}', ['as' => 'admin.faq.edit', 'uses' => 'FaqController@edit']);

            //Faq Update
            Route::post('update/{faq_id}', ['as' => 'admin.faq.update', 'uses' => 'FaqController@update']);

            //Faq Update
            Route::post('delete/{faq_id}', ['as' => 'admin.faq.destroy', 'uses' => 'FaqController@destroy']);

            //Faq Update
            Route::post('restore/{faq_id}', ['as' => 'admin.faq.restore', 'uses' => 'FaqController@restore']);
        });

        //COURIERS
        Route::group(['prefix' => 'courier'], function () {
            //Courier Index
            Route::get('/', ['as' => 'admin.courier.index', 'uses' => 'CourierController@index']);

            //Courier Create
            Route::get('create', ['as' => 'admin.courier.create', 'uses' => 'CourierController@create']);

            //Courier Store
            Route::post('/', ['as' => 'admin.courier.store', 'uses' => 'CourierController@store']);

            //Courier Edit
            Route::get('edit/{courier_id}', ['as' => 'admin.courier.edit', 'uses' => 'CourierController@edit']);

            //Courier Update
            Route::post('update/{courier_id}', ['as' => 'admin.courier.update', 'uses' => 'CourierController@update']);
        });

        //PROMOTIONS
        Route::group(['prefix' => 'promotions'], function () {
            //Promotion Index
            Route::get('/', ['as' => 'admin.promotion.index', 'uses' => 'PromotionController@index']);

            //Promotion Create
            Route::get('create', ['as' => 'admin.promotion.create', 'uses' => 'PromotionController@create']);

            //Promotion Store
            Route::post('/', ['as' => 'admin.promotion.store', 'uses' => 'PromotionController@store']);

            //Promotion Show
            Route::get('view/{promotion_id}', ['as' => 'admin.promotion.show', 'uses' => 'PromotionController@show']);

            //Promotion Destroy
            Route::post('delete/{promotion_id}', ['as' => 'admin.promotion.destroy', 'uses' => 'PromotionController@destroy']);
        });
    });

    //SHIPPING FEES
    Route::group(['prefix' => 'shipping_fees'], function () {

        //Shipping Fee Index
        Route::get('/', ['as' => 'admin.shipping_fee.index', 'uses' => 'ShippingFeeController@index']);

        //Shipping Fee Show
        Route::get('view/{shipping_fee_id}', ['as' => 'admin.shipping_fee.show', 'uses' => 'ShippingFeeController@show']);

        //Shipping Fee Store
        Route::post('/', ['as' => 'admin.shipping_fee.store', 'uses' => 'ShippingFeeController@store']);

        //Shipping Fee Edit
        Route::get('edit/{shipping_fee_id}', ['as' => 'admin.shipping_fee.edit', 'uses' => 'ShippingFeeController@edit']);

        //Shipping Fee Update
        Route::post('update/{shipping_fee_id}', ['as' => 'admin.shipping_fee.update', 'uses' => 'ShippingFeeController@update']);
    });

    //ORDERS
    Route::group(['prefix' => 'orders'], function () {
        //Orders Index
        Route::get('/', ['as' => 'admin.order.index', 'uses' => 'OrderController@index']);

        //Orders Show
        Route::get('view/{order_code}', ['as' => 'admin.order.show', 'uses' => 'OrderController@show']);

        //Orders Deliver
        Route::post('deliver/{order_code}', ['as' => 'admin.order.deliver', 'uses' => 'OrderController@deliver']);

        //Orders Return
        Route::post('return/{order_code}', ['as' => 'admin.order.return', 'uses' => 'OrderController@return']);

        //Orders Print
        Route::get('print/{order_code}', ['as' => 'admin.order.print', 'uses' => 'OrderController@print']);
    });

    //SALES
    Route::group(['prefix' => 'sales'], function () {
        //Sales Index
        Route::get('/', ['as' => 'admin.sales.index', 'uses' => 'SalesController@index']);

        //Printing
        Route::get('print', ['as' => 'admin.sales.print', 'uses' => 'SalesController@print']);
    });

    //CUSTOMERS
    Route::group(['prefix' => 'customers'], function () {
        //Customers Index
        Route::get('/', ['as' => 'admin.customer.index', 'uses' => 'CustomerController@index']);

        //Customers Show
        Route::get('view/{customer_id}', ['as' => 'admin.customer.show', 'uses' => 'CustomerController@show']);

        //Customers Print
        Route::get('print', ['as' => 'admin.customer.print', 'uses' => 'CustomerController@print']);
    });

    //Reports Page
    Route::get('reports', ['as' => 'admin.report.index', 'uses' => 'ReportController@index']);

    //Sign Out
    Route::get('signout', ['as' => 'admin.signout', 'uses' => 'UserController@sign_out']);
});

//Product Photo
Route::get('image/{directory}/{image_name}', ['as' => 'image', 'uses' => 'PhotoController@show']);
