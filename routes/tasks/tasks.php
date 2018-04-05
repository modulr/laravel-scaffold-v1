<?php

Route::group(['namespace' => 'Tasks'], function() {
    // Views
    Route::group(['prefix' => 'tasks'], function() {
        Route::get('/' , function () {
            return view('tasks.tasks');
        })->middleware('permission:read-tasks');
    });
    // API
    Route::group(['prefix' => 'api/tasks'], function() {
        // Find
        Route::get('/byCreator', 'TaskController@byCreator')->middleware('permission:read-tasks');
        // CRUD
        Route::post('/store', 'TaskController@store')->middleware('permission:create-tasks');
        //Route::put('/update/{id}', 'TaskController@update')->middleware('permission:update-tasks');
        Route::delete('/destroy/{id}', 'TaskController@destroy')->middleware('permission:delete-tasks');
        Route::put('/markDone/{id}', 'TaskController@markDone')->middleware('permission:update-tasks');
        Route::put('/updateOrder', 'TaskController@updateOrder')->middleware('permission:update-tasks');
    });
});
