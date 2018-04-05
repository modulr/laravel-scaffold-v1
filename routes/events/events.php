<?php

Route::group(['namespace' => 'Events'], function() {
    // Views
    Route::group(['prefix' => 'events'], function() {
        Route::get('/' , function () {
            return view('events.events');
        })->middleware('permission:read-events');
        Route::get('/{id}' , function ($id) {
            return view('events.event', ['id' => $id]);
        })->middleware('permission:read-events');
    });

    // API
    Route::group(['prefix' => 'api/events'], function() {
        // Events Find
        Route::get('/all', 'EventController@all')->middleware('permission:read-events');
        Route::get('/byCreator', 'EventController@byCreator')->middleware('permission:read-events');
        Route::get('/show/{id}', 'EventController@show')->middleware('permission:read-events');
        // Events CRUD
        Route::post('/store', 'EventController@store')->middleware('permission:create-events');
        Route::put('/update/{id}', 'EventController@update')->middleware('permission:update-events');
        Route::delete('/destroy/{id}', 'EventController@destroy')->middleware('permission:delete-events');
        // Images
        Route::group(['prefix' => 'images'], function() {
            Route::post('/upload/temp', 'EventController@uploadImageTemp')->middleware('permission:create-news');
            Route::post('/upload', 'EventController@uploadImage')->middleware('permission:update-news');
            Route::post('/sort/{eventId}', 'EventController@sortImage')->middleware('permission:update-news');
            Route::delete('/destroy/{id}', 'EventController@destroyImage')->middleware('permission:delete-events');
        });
    });
});
