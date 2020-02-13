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

    //Orders Page
    Route::get('orders', ['as' => 'admin.order.index', 'uses' => 'OrderController@index']);

    //Products Page
    Route::get('products', ['as' => 'admin.product.index', 'uses' => 'ProductController@index']);

    //Customers Page
    Route::get('customers', ['as' => 'admin.customer.index', 'uses' => 'CustomerController@index']);

    //Sales Page
    Route::get('sales', ['as' => 'admin.sales.index', 'uses' => 'SalesController@index']);

    //Reports Page
    Route::get('reports', ['as' => 'admin.report.index', 'uses' => 'ReportController@index']);

    //Sign Out
    Route::get('signout', ['as' => 'admin.signout', 'uses' => 'UserController@sign_out']);
});
