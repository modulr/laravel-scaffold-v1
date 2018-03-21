<?php

Route::group(['namespace' => 'News'], function() {
    // Views
    Route::group(['prefix' => 'news'], function() {
        Route::get('/' , function () {
            return view('news.news');
        })->middleware('permission:read-news');
        Route::get('/{id}' , function ($id) {
            return view('news.item', ['id' => $id]);
        })->middleware('permission:read-news');
    });
    // Views
    Route::group(['prefix' => 'api/news'], function() {
        // Find
        Route::get('/all', 'NewsController@all')->middleware('permission:read-news');
        Route::get('/show/{id}', 'NewsController@show')->middleware('permission:read-news');
        // CRUD
        Route::post('/store', 'NewsController@store')->middleware('permission:create-news');
        Route::delete('/destroy/{id}', 'NewsController@destroy')->middleware('permission:delete-news');
        Route::post('/like/{id}', 'NewsController@like')->middleware('permission:read-news');
        // Images
        Route::post('/upload/temp', 'NewsController@uploadTemp')->middleware('permission:create-news');
    });
});
