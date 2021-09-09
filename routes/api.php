<?php

use Illuminate\Support\Facades\Route;

Route::namespace('api')->get('home', 'HomeController@index');

Route::namespace('api')->post('home/best-sellers', 'HomeController@bestSellers');

Route::namespace('api')->post('home/sales-products', 'HomeController@products');

Route::namespace('api')->post('home/new-products', 'HomeController@newProducts');

Route::namespace('api')->get('menu', 'HomeController@menu');

Route::namespace('api')->get('footer', 'HomeController@footer');

Route::namespace('api')->post('category/products/{id}', 'CategoryController@getCategoryProducts');

Route::namespace('api')->post('category/products/{id}/{children_id}', 'CategoryController@getSubCategory');

Route::namespace('api')->post('accessory/products/{id}', 'AccessoryController@getAccessory');

Route::namespace('api')->get('info-page/{id}', 'ArticleController@getArticle');

Route::namespace('api')->get('image', 'CategoryController@getImage');

Route::namespace('api')->get('categoryDetails/{id}', 'CategoryController@getCategoryDetails');

Route::namespace('api')->get('accessoryDetails/{id}', 'AccessoryController@getAccessoryDetails');

Route::namespace('api')->post('distributionOffer', 'DistributionOfferController@createOffer');

Route::group(['namespace' => 'Api'], function () {

    Route::post('fetchProducts', 'ProductController@getProducts')
            ->name('create.fetch.product');

    Route::post('auth/user', 'UserController@getAuthUser')
        ->name('get.auth.user');

    Route::group(['prefix' => 'sales'], function () {
        Route::post('/global', 'SalesController@getGlobalSales')
            ->name('sales.global.all');
    });

    Route::group(['prefix' => 'sales'], function () {
        Route::post('/group', 'SalesController@getGroupSales')
            ->name('sales.group.all');
    });

    Route::group(['prefix' => 'products'], function () {
        Route::post('/recommended', 'ProductController@getRecommendedProduct')
            ->name('sales.global.all');
    });

    Route::namespace('api')
        ->get('product/{id}', 'ProductController@getProduct');

    Route::get('order-status/{token}', 'CheckoutController@getOrder')
        ->name('order-status.get');

    Route::group(['prefix' => 'checkout'], function () {
        Route::post('/regions', 'CheckoutController@getRegions')
            ->name('checkout.regions.get');

        Route::post('/cities', 'CheckoutController@getRegionCities')
            ->name('checkout.cities.get');

        Route::post('/postal/offices', 'CheckoutController@getPostalOffices')
            ->name('checkout.postal.offices.get');

        Route::post('/payment/methods', 'CheckoutController@getPaymentMethods')
            ->name('payment.methods.get');

        Route::post('/delivery/methods', 'CheckoutController@getDeliveryMethods')
            ->name('delivery.methods.get');

        Route::post('/create/order', 'CheckoutController@createOrder')
            ->name('create.order.get');

        Route::post('/create/orderQuick', 'CheckoutController@createQuickOrder')
            ->name('create.order.quick');

        Route::post('/create/orderQuickFromProduct', 'CheckoutController@createQuickOrderFromProduct')
            ->name('create.order.quickFromProduct');

        Route::post('/create/preOrder', 'CheckoutController@createPreOrder')
            ->name('create.order.preOrder');

        Route::post('/create/unfinishedOrder', 'CheckoutController@createUnfinishedOrder')
            ->name('create.order.unfinishedOrder');
    });
});

//Route::namespace('api')->post('login', 'Auth\LoginController@loginToProfile');
//Route::namespace('api')->post('loggedOut', 'Auth\LoginController@loggedOut');

Route::post('register', 'Auth\RegisterController@create');
Route::post('newVerifyCode', 'Auth\RegisterController@getNewVerifyCode');
Route::post('login', 'AuthController@login');
Route::post('logout', 'AuthController@logout');
Route::post('refresh', 'AuthController@refresh');
Route::post('me', 'AuthController@me');
Route::post('checkUser', 'AuthController@checkUser');
Route::post('profile', 'ProfileController@getProfile');
Route::post('profileOrderProducts/{id}', 'ProfileController@getOrderProducts');
Route::post('updateProfile', 'ProfileController@updateProfile');
Route::post('changePassword', 'ProfileController@changePassword');
Route::post('addImage', 'ProfileController@addImage');
Route::post('deleteImage', 'ProfileController@deleteImage');
Route::post('addEmailForReceive', 'HomeController@addEmailForReceive');
Route::get('getMainCategories', 'HomeController@getMainCategories');
Route::get('getGroupSales', 'ProfileController@getGroupSales');
Route::post('verifyUser', 'AuthController@verifyUser');
Route::post('sendOrderStatus', 'CheckoutController@sendOrderStatus');
Route::post('sendInvite', 'ProfileController@sendInvite');
Route::post('addUserToGroup', 'ProfileController@addUserToGroup');
