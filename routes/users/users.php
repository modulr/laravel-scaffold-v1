<?php

Route::group(['namespace' => 'Users'], function() {
    // Views Users
    Route::group(['prefix' => 'users'], function() {
        Route::get('/', function () {
            return view('users.users');
        })->middleware('permission:read-users');
    });
    // API Users
    Route::group(['prefix' => 'api/users'], function() {
        // Find
        Route::get('/all', 'UserController@all')->middleware('permission:read-users');
        Route::get('/filter', 'UserController@filter')->middleware('permission:read-users');
        // CRUD
        Route::post('/store', 'UserController@store')->middleware('permission:create-users');
        Route::put('/update/{id}', 'UserController@update')->middleware('permission:update-users');
        Route::put('/updatePassword/{id}', 'UserController@updatePassword')->middleware('permission:update-users');
        Route::delete('/destroy/{id}', 'UserController@destroy')->middleware('permission:delete-users');
        // Avatar
        Route::post('/upload/avatar/temp', 'UserController@uploadAvatarTemp')->middleware('permission:update-users');
        Route::post('/upload/avatar', 'UserController@uploadAvatar')->middleware('permission:update-users');
    });
    // Views Roles
    Route::group(['prefix' => 'roles'], function() {
        Route::get('/' , function () {
            return view('users.roles');
        })->middleware('permission:read-roles');
    });
    // API Roles
    Route::group(['prefix' => 'api/roles'], function() {
        // Find
        Route::get('/all', 'RoleController@all')->middleware('permission:read-roles');
        Route::get('/filter', 'RoleController@filter')->middleware('permission:read-roles');
        Route::get('/{id}', 'RoleController@show')->middleware('permission:read-roles');
        // CRUD
        Route::post('/store', 'RoleController@store')->middleware('permission:create-roles');
        Route::put('/update/{id}', 'RoleController@update')->middleware('permission:update-roles');
        Route::delete('/destroy/{id}', 'RoleController@destroy')->middleware('permission:delete-roles');
    });
    // API Permissions
    Route::group(['prefix' => 'api/permissions'], function() {
        // Find
        Route::get('/all', 'PermissionController@all');
    });
});
