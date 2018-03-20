<?php
Route::group(['namespace' => 'Autoparts'], function() {

    // Modul.os
    Route::group(['prefix' => 'autoparts'], function() {
        Route::get('/sales', function () {return view('autoparts.sales');})->middleware('permission:update-autoparts');
        Route::get('/inventory', function () {return view('autoparts.inventory');})->middleware('permission:create-autoparts');
        Route::get('/lists', function () {return view('autoparts.lists');})->middleware('permission:create-autoparts');
        //Route::get('/{id}', function ($id) {return view('autoparts.autopart', ['id' => $id]);})->middleware('permission:read-autoparts');
    });

    Route::group(['prefix' => 'api/autoparts'], function() {
        // Autoparts Search
        Route::get('/all', 'AutopartController@all')->middleware('permission:read-autoparts');
        Route::post('/filter', 'AutopartController@filter')->middleware('permission:read-autoparts');
        Route::get('/show/{id}', 'AutopartController@show')->middleware('permission:read-autoparts');

        // Autoparts CRUD
        Route::post('/store', 'AutopartController@store')->middleware('permission:create-autoparts');
        Route::put('/update/{id}', 'AutopartController@update')->middleware('permission:update-autoparts');
        Route::put('/updateStatus/{id}', 'AutopartController@updateStatus')->middleware('permission:update-autoparts');
        Route::delete('/destroy/{id}', 'AutopartController@destroy')->middleware('permission:delete-autoparts');

        // Images Upload
        Route::group(['prefix' => 'images'], function() {
            Route::post('/upload/temp', 'AutopartController@uploadImageTemp')->middleware('permission:create-autoparts');
            Route::post('/upload', 'AutopartController@uploadImage')->middleware('permission:update-autoparts');
            Route::delete('/destroy/{id}', 'AutopartController@destroyImage')->middleware('permission:delete-autoparts');
            Route::post('/sort/{autopartId}', 'AutopartController@sortImage')->middleware('permission:update-autoparts');
        });

        // Comments
        Route::group(['prefix' => 'comments'], function() {
            Route::get('/all', 'AutopartCommentController@index');
            Route::get('/show/{id}', 'AutopartCommentController@show');
            Route::post('/store', 'AutopartCommentController@store');
            Route::put('/update/{id}', 'AutopartCommentController@update');
            Route::delete('/destroy/{id}', 'AutopartCommentController@destroy');
        });

        // Lists
        Route::group(['prefix' => 'lists'], function() {
            Route::get('/makes', 'AutopartController@makes')->middleware('permission:read-autoparts');
            Route::get('/makesFull', 'AutopartController@makesFull')->middleware('permission:read-autoparts');
            Route::post('/makes/store', 'AutopartController@storeMake')->middleware('permission:create-autoparts');
            Route::delete('/makes/destroy/{id}', 'AutopartController@destroyMake')->middleware('permission:delete-autoparts');
            //Route::put('/makes/order', 'AutopartController@orderMake')->middleware('permission:update-autoparts');

            Route::get('/models', 'AutopartController@models')->middleware('permission:read-autoparts');
            Route::post('/models/store', 'AutopartController@storeModel')->middleware('permission:create-autoparts');
            Route::delete('/models/destroy/{id}', 'AutopartController@destroyModel')->middleware('permission:delete-autoparts');
            //Route::put('/models/order', 'AutopartController@orderModel')->middleware('permission:update-autoparts');

            Route::get('/years', 'AutopartController@years')->middleware('permission:read-autoparts');
            Route::post('/years/store', 'AutopartController@storeYear')->middleware('permission:create-autoparts');
            Route::delete('/years/destroy/{id}', 'AutopartController@destroyYear')->middleware('permission:delete-autoparts');
            //Route::put('/years/order', 'AutopartController@orderYear')->middleware('permission:update-autoparts');

            Route::get('/origins', 'AutopartController@origins')->middleware('permission:read-autoparts');
            Route::get('/status', 'AutopartController@status')->middleware('permission:read-autoparts');
        });
    });
});
