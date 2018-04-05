<?php

Route::group(['namespace' => 'Files'], function() {
    // Views
    Route::group(['prefix' => 'files'], function() {
        Route::get('/' , function () {
            return view('files.files');
        })->middleware('permission:read-files');
        Route::get('/folder/{folderId?}' , function () {
            return view('files.files');
        })->middleware('permission:read-files');
    });
    // API
    Route::group(['prefix' => 'api/files'], function() {
        // Find
        Route::get('/byCreator/{parentId?}', 'FileController@byCreator')->middleware('permission:read-files');
        // CRUD
        Route::post('/store', 'FileController@store')->middleware('permission:create-files');
        Route::put('/update/{id}', 'FileController@update')->middleware('permission:update-files');
        Route::delete('/destroy/{id}', 'FileController@destroy')->middleware('permission:delete-files');
    });
});
