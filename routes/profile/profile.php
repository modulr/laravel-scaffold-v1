<?php

Route::group(['namespace' => 'Profile'], function() {
    // Views
    Route::group(['prefix' => 'profile'], function() {
        Route::get('/{id}', 'ProfileController@profile');
        Route::get('/{id}/edit', 'ProfileController@profileEdit')
            ->middleware('permission:update-own-profile|update-all-profile');

        Route::get('/{id}/work', 'ProfileController@work');
        Route::get('/{id}/work/edit', 'ProfileController@workEdit')
            ->middleware('permission:update-own-profile|update-all-profile');

        Route::get('/{id}/password/edit', 'ProfileController@password')
            ->middleware('permission:update-own-profile|update-all-profile');
    });

    // API
    Route::group(['prefix' => 'api/profile'], function() {
        // Profile CRUD
        Route::post('/contact/store', 'ProfileController@storeContact')
            ->middleware('permission:update-own-profile|update-all-profile');
        Route::delete('/contact/destroy/{id}', 'ProfileController@destroyContact')
            ->middleware('permission:update-own-profile|update-all-profile');

        Route::post('/education/store', 'ProfileController@storeEducation')
            ->middleware('permission:update-own-profile|update-all-profile');
        Route::delete('/education/destroy/{id}', 'ProfileController@destroyEducation')
            ->middleware('permission:update-own-profile|update-all-profile');

        Route::post('/family/store', 'ProfileController@storeFamily')
            ->middleware('permission:update-own-profile|update-all-profile');
        Route::delete('/family/destroy/{id}', 'ProfileController@destroyFamily')
            ->middleware('permission:update-own-profile|update-all-profile');

        Route::put('/personal/update/{id}', 'ProfileController@updatePersonal')
            ->middleware('permission:update-own-profile|update-all-profile');

        Route::post('/place/store', 'ProfileController@storePlace')
            ->middleware('permission:update-own-profile|update-all-profile');
        Route::delete('/place/destroy/{id}', 'ProfileController@destroyPlace')
            ->middleware('permission:update-own-profile|update-all-profile');

        Route::put('/work/update/{id}', 'ProfileController@updateWork')
            ->middleware('permission:update-own-profile|update-all-profile');
        
        // Lists
        Route::group(['prefix' => 'lists'], function() {
            Route::get('/gender', 'ProfileListController@gender');
            Route::get('/relationship', 'ProfileListController@relationship');
            Route::get('/contact', 'ProfileListController@contact');
            Route::get('/relation', 'ProfileListController@relation');
            Route::get('/profession', 'ProfileListController@profession');
            Route::get('/position', 'ProfileListController@position');
            Route::get('/department', 'ProfileListController@department');
        });
    });
});
