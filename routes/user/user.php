<?php

Route::group(['namespace' => 'Users'], function() {
    // Users
    Route::post('/api/users/upload/avatar', 'UserController@uploadAvatar2')->middleware('permission:update-users|update-profile');
    Route::group(['prefix' => 'users'], function() {
        Route::get('/', 'UserController@index')->middleware('permission:read-users');
        Route::get('/all', 'UserController@all')->middleware('permission:read-users');
        Route::post('/store', 'UserController@store')->middleware('permission:create-users');
        Route::put('/update/{id}', 'UserController@update')->middleware('permission:update-users');
        Route::put('/updatePassword/{id}', 'UserController@updatePassword')->middleware('permission:update-users|update-profile');
        Route::delete('/destroy/{id}', 'UserController@destroy')->middleware('permission:delete-users');
        Route::post('/upload/avatar/temp', 'UserController@uploadAvatarTemp')->middleware('permission:update-users');
        Route::post('/upload/avatar/{id}', 'UserController@uploadAvatar')->middleware('permission:update-users');
    });
    // Roles
    Route::group(['prefix' => 'roles'], function() {
        Route::get('/', 'RoleController@index')->middleware('permission:read-roles');
        Route::get('/all', 'RoleController@all')->middleware('permission:read-roles');
        Route::get('/{id}', 'RoleController@show')->middleware('permission:read-roles');
        Route::post('/store', 'RoleController@store')->middleware('permission:create-roles');
        Route::put('/update/{id}', 'RoleController@update')->middleware('permission:update-roles');
        Route::delete('/destroy/{id}', 'RoleController@destroy')->middleware('permission:delete-roles');
    });
    // Permissions
    Route::group(['prefix' => 'permissions'], function() {
        Route::get('/all', 'PermissionController@all');
    });
});
