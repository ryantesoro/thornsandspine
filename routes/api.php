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

Route::post('verify', ['as' => 'verify', 'uses' => 'VerificationController@verify']);
Route::post('resend', ['as' => 'resend', 'uses' => 'VerificationController@resend']);

Route::group(['middleware' => 'auth:api'], function() {
    Route::get('user', ['as' => 'user', 'uses' => 'UserController@user']);
    Route::get('test', ['as' => 'test', 'uses' => 'UserController@test']);
});