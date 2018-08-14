<?php

Route::group(['namespace' => 'Profile'], function() {
    // Views
    Route::group(['prefix' => 'profile'], function() {
        Route::get('/{id}', 'ProfileController@profile');
        Route::get('/{id}/edit', 'ProfileController@profileEdit');

        Route::get('/{id}/password/edit', 'ProfileController@password');
    });

    // API
    Route::group(['prefix' => 'api/profile'], function() {
        // Profile CRUD
        Route::put('/update/{id}', 'ProfileController@updateProfile');

        Route::post('/contact/store', 'ProfileController@storeContact');
        Route::delete('/contact/destroy/{id}', 'ProfileController@destroyContact');

        Route::post('/place/store', 'ProfileController@storePlace');
        Route::delete('/place/destroy/{id}', 'ProfileController@destroyPlace');

        // Lists
        Route::group(['prefix' => 'lists'], function() {
            Route::get('/contact', 'ProfileListController@contact');
        });
    });
});
