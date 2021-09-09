<?php

/**
 * Routers for 1C urls.
 * */
Route::group(['prefix' => 'import'], function () {

    Route::get('/get/data', 'ImportTo1CController@getDataToImport');
    Route::get('/get/test_data', 'ImportTo1CController@getTestDataToImport');
    Route::get('/get/hard_data', 'ImportTo1CController@getDataToImport');
    Route::post('/get/data', 'ImportTo1CController@getDataToImport');
    Route::get('/get/test_data', 'ImportTo1CController@getTestDataToImport');
    Route::post('/get/hard_data', 'ImportTo1CController@getDataToImport');
});
