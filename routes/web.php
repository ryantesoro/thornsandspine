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
Route::group(['middleware' => 'auth'], function() {

    //Dashboard Page
    Route::get('dashboard', ['as' => 'admin.dashboard', 'uses' => 'HomeController@dashboard']);

    //PRODUCTS
    Route::group(['prefix' => 'products'], function() {

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

    Route::group(['prefix' => 'pots'], function() {

        //Pot Index
        Route::get('/', ['as' => 'admin.pot.index', 'uses' => 'PotController@index']);

        //Pot Create
        Route::get('create', ['as' => 'admin.pot.create', 'uses' => 'PotController@create']);

        //Pot Store
        Route::post('/', ['as' => 'admin.pot.store', 'uses' => 'PotController@store']);

        //Pot Show
        Route::get('{pot_id}', ['as' => 'admin.pot.show', 'uses' => 'PotController@show']);

        //Pot Edit
        Route::get('edit/{pot_id}', ['as' => 'admin.pot.edit', 'uses' => 'PotController@edit']);

        //Pot Update
        Route::post('update/{pot_id}', ['as' => 'admin.pot.update', 'uses' => 'PotController@update']);

        //Pot Delete
        Route::post('delete/{pot_id}', ['as' => 'admin.pot.destroy', 'uses' => 'PotController@destroy']);

        //Pot Delete
        Route::post('restore/{pot_id}', ['as' => 'admin.pot.restore', 'uses' => 'PotController@restore']);
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
