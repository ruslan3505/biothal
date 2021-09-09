<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::any('portmone/success/{order_id}', 'PortmoneController@success')->name('portmone.success');
Route::any('portmone/canceled/{order_id}', 'PortmoneController@cancel')->name('portmone.cancel');

Auth::routes();
Route::get('logout', 'Auth\LoginController@logout');

Route::get('/', 'HomeController@web');
Route::get('home', 'HomeController@index')->name('home');
Route::get('company/about', 'HomeController@about');
Route::get('company/sea', 'HomeController@sea');
Route::get('company/vod', 'HomeController@vod');
Route::get('company/production', 'HomeController@production');
Route::get('category/{id}', 'CategoryController@getParentCategory');
Route::get('category/{parent_id}/{id}', 'CategoryController@getCategory')->name('category');
Route::get('accessory/{id}', 'AccessoryController@getParentAccessory');
Route::get('accessory/{parent_id}/{id}', 'AccessoryController@getAccessory')->name('accessory');
Route::get('product/{id}', 'ProductController@getProduct')->name('products_id');

Route::group(['middleware' => 'auth', 'prefix' => 'admin'], function () {
    Route::post('test1', 'TestController@test')->name('test1');
});

Route::get('test', 'TestController@index')->name('test');
Route::get('test/ip','TestController@getMailing');
Route::get('test/pagin', 'TestController@home');
Route::get('stripe', 'StripePaymentController@stripe')->middleware('test');
Route::post('stripe', 'StripePaymentController@stripePost')->name('stripe.post')->middleware('test');

Route::get('portmone', 'PortmoneController@index');


Route::get('face', 'FaceController@getAllCategory');
Route::post('buyCartHome', 'CartController@insInCartHome');
Route::post('buyCart', 'CartController@insInCart');

Route::post('plus_count', 'CartController@plus_count');
Route::post('minus_count', 'CartController@minus_count');

Route::post('delCart', 'CartController@delCart');

Route::get('setCheck', 'CartController@setCheck');
Route::post('checkout', 'CartController@checkout');
Route::post('check', 'CartController@check');
Route::post('checkModalOneClick', 'CartController@checkModalOneClick');
Route::post('session_destroy', 'CartController@sessionDestroy');

Route::get('getxml', 'XmlController@getXml');
Route::get('xmlto1c', 'XmlController@xmlto1c');




