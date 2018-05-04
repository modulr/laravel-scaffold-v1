<?php

Route::group(['namespace' => 'Profile'], function() {
    // Views
    Route::group(['prefix' => 'profile'], function() {
        Route::get('/{id}', 'ProfileController@profile');
        Route::get('/{id}/edit', 'ProfileController@profileEdit');

        Route::get('/{id}/work', 'ProfileController@work');
        Route::get('/{id}/work/edit', 'ProfileController@workEdit');

        Route::get('/{id}/password/edit', 'ProfileController@password');
    });

    // API
    Route::group(['prefix' => 'api/profile'], function() {
        // Profile CRUD
        Route::put('/update/{id}', 'ProfileController@updateProfile');

        Route::post('/contact/store', 'ProfileController@storeContact');
        Route::delete('/contact/destroy/{id}', 'ProfileController@destroyContact');

        Route::post('/education/store', 'ProfileController@storeEducation');
        Route::delete('/education/destroy/{id}', 'ProfileController@destroyEducation');

        Route::post('/family/store', 'ProfileController@storeFamily');
        Route::delete('/family/destroy/{id}', 'ProfileController@destroyFamily');

        Route::put('/personal/update/{id}', 'ProfileController@updatePersonal');

        Route::post('/place/store', 'ProfileController@storePlace');
        Route::delete('/place/destroy/{id}', 'ProfileController@destroyPlace');

        Route::put('/work/update/{id}', 'ProfileController@updateWork');

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
