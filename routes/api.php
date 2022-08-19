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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['namespace' => 'Api'], function () {
    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');
    Route::post('reset', 'AuthController@reset');
    Route::post('otp/send', 'OtpController@send');
    Route::post('otp/verify', 'OtpController@verify');
    
    Route::group(['middleware'=>['auth.jwt']], function () {
        Route::post('profile/change-password', 'ProfileController@changePassword');
        Route::post('profile/update', 'ProfileController@update');
        Route::get('category-brand', 'CommonController@getCategoryOrBrand');
        Route::post('media/store/{id}', 'MediaController@store');
        Route::post('media/delete/{id}', 'MediaController@destroy');
        Route::post('review/store/{id}', 'ReviewController@store');
    });

    Route::group(['prefix'=>'','namespace' => 'WholeSaler','middleware'=>['auth.jwt']], function () {
        Route::post('product/store', 'ProductController@store');
        Route::get('product', 'ProductController@index');
        Route::get('product/{id}', 'ProductController@edit');
        Route::post('product/update/{id}', 'ProductController@update');
        Route::get('order', 'OrderController@index');
        Route::get('order/{id}', 'OrderController@show');
        Route::get('order-new', 'OrderController@newOrder');
        Route::post('order/update/{id}', 'OrderController@updateOrder');
    });

    Route::group(['prefix'=>'','namespace' => 'Lab','middleware'=>['auth.jwt']], function () {
        Route::get('service', 'ServiceController@index');
        Route::get('service/{id}', 'ServiceController@show');
        Route::post('service/store', 'ServiceController@store');
        Route::post('service/update/{id}', 'ServiceController@update');
    });

    Route::group(['prefix'=>'customer','namespace' => 'Customer','middleware'=>['auth.jwt']], function () {
        Route::get('product', 'ProductController@index');
        Route::get('product/{id}', 'ProductController@show');
        Route::get('order', 'OrderController@index');
        Route::get('order/{id}', 'OrderController@show');
        Route::post('add-or-remove-favourite/{id}', 'FavouriteController@addOrRemoveFavourite');
        Route::get('favourites', 'FavouriteController@index');
    });
    
});
