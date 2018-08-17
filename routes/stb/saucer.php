<?php

Route::group(['namespace' => 'Stb'], function() {
    // Views
    Route::get('/platillos' , function () {
        return view('stb.saucers'); })->middleware('permission:read-events');
    Route::get('/platillos/{id}' , function ($id) {
        return view('stb.saucer', ['id' => $id]); })->middleware('permission:read-events');

    Route::get('/mis_platillos' , function () {
        return view('stb.mysaucers'); })->middleware('permission:read-events');
    Route::get('/mis_platillos/{id}' , function ($id) {
        return view('stb.mysaucer', ['id' => $id]); })->middleware('permission:read-events');

    // CRUD
    Route::group(['prefix' => 'saucer'], function() {
        Route::get('/byOwner', 'SaucerController@byOwner')->middleware('permission:read-events');
        Route::get('/{id}', 'SaucerController@show')->middleware('permission:read-events');

        Route::post('/store', 'SaucerController@store')->middleware('permission:create-events');
        Route::put('/update/{id}', 'SaucerController@update')->middleware('permission:update-events');
        Route::delete('/destroy/{id}', 'SaucerController@destroy')->middleware('permission:delete-events');
        Route::get('/enable/{id}', 'SaucerController@toogleEnabled')->middleware('permission:update-events');

        Route::post('/images/upload/temp', 'SaucerController@uploadImageTemp')->middleware('permission:create-news');
        Route::post('/images/upload/', 'SaucerController@uploadImage')->middleware('permission:update-news');
        Route::post('/images/sort/{eventId}', 'SaucerController@sortImage')->middleware('permission:update-news');
        Route::delete('/images/destroy/{id}', 'SaucerController@destroyImage')->middleware('permission:delete-events');
    });
});
