<?php

Route::group(['namespace' => 'Autoparts'], function() {
    // Views
    Route::group(['prefix' => 'autoparts'], function() {
        Route::get('/sales', function () {return view('autoparts.sales');})->middleware('permission:read-sales');
        Route::get('/inventory', function () {return view('autoparts.inventory');})->middleware('permission:read-inventory');
        Route::get('/lists', function () {return view('autoparts.lists');})->middleware('permission:read-inventory-lists');
        //Route::get('/{id}', function ($id) {return view('autoparts.autopart', ['id' => $id]);})->middleware('permission:read-autoparts');
    });

    // API
    Route::group(['prefix' => 'api/autoparts'], function() {
        // Sales/Inventory Find
        // Route::get('/all', 'AutopartController@all')->middleware('permission:read-inventory|read-sales');
        Route::post('/filter', 'AutopartController@filter')->middleware('permission:read-inventory|read-sales');
        Route::get('/show/{id}', 'AutopartController@show')->middleware('permission:read-inventory|read-sales');
        // Inventory CRUD
        Route::post('/store', 'AutopartController@store')->middleware('permission:create-inventory');
        Route::put('/update/{id}', 'AutopartController@update')->middleware('permission:update-inventory');
        Route::delete('/destroy/{id}', 'AutopartController@destroy')->middleware('permission:delete-inventory');
        // Sales Update
        Route::put('/updateStatus/{id}', 'AutopartController@updateStatus')->middleware('permission:update-sales');
        // Images Upload
        Route::group(['prefix' => 'images'], function() {
            Route::post('/upload/temp', 'AutopartController@uploadImageTemp')->middleware('permission:create-inventory');
            Route::post('/upload', 'AutopartController@uploadImage')->middleware('permission:update-inventory');
            Route::delete('/destroy/{id}', 'AutopartController@destroyImage')->middleware('permission:delete-inventory');
            Route::post('/sort/{autopartId}', 'AutopartController@sortImage')->middleware('permission:update-inventory');
        });
        // Comments
        Route::group(['prefix' => 'comments'], function() {
            Route::get('/all', 'AutopartCommentController@index');
            Route::get('/show/{id}', 'AutopartCommentController@show');
            Route::post('/store', 'AutopartCommentController@store');
            Route::put('/update/{id}', 'AutopartCommentController@update');
            Route::delete('/destroy/{id}', 'AutopartCommentController@destroy');
        });
        // Inventory Lists
        Route::group(['prefix' => 'lists'], function() {
            Route::get('/makes', 'AutopartController@makes')->middleware('permission:read-inventory-lists|read-inventory|read-sales');
            Route::get('/makesFull', 'AutopartController@makesFull')->middleware('permission:read-inventory-lists');
            Route::post('/makes/store', 'AutopartController@storeMake')->middleware('permission:create-inventory-lists');
            Route::delete('/makes/destroy/{id}', 'AutopartController@destroyMake')->middleware('permission:delete-inventory-lists');
            //Route::put('/makes/order', 'AutopartController@orderMake')->middleware('permission:update-autoparts');

            Route::get('/models', 'AutopartController@models')->middleware('permission:read-inventory-lists|read-inventory|read-sales');
            Route::post('/models/store', 'AutopartController@storeModel')->middleware('permission:create-inventory-lists');
            Route::delete('/models/destroy/{id}', 'AutopartController@destroyModel')->middleware('permission:delete-inventory-lists');
            //Route::put('/models/order', 'AutopartController@orderModel')->middleware('permission:update-autoparts');

            Route::get('/years', 'AutopartController@years')->middleware('permission:read-inventory-lists|read-inventory|read-sales');
            Route::post('/years/store', 'AutopartController@storeYear')->middleware('permission:create-inventory-lists');
            Route::delete('/years/destroy/{id}', 'AutopartController@destroyYear')->middleware('permission:delete-inventory-lists');
            //Route::put('/years/order', 'AutopartController@orderYear')->middleware('permission:update-autoparts');

            Route::get('/origins', 'AutopartController@origins')->middleware('permission:read-inventory');
            Route::get('/status', 'AutopartController@status')->middleware('permission:read-inventory');
        });
    });
});
