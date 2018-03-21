<?php

Route::group(['namespace' => 'Contacts'], function() {
    // Views
    Route::group(['prefix' => 'contacts'], function() {
        Route::get('/' , function () {
            return view('contacts.contacts');
        })->middleware('permission:read-contacts');
    });

    // API
    Route::group(['prefix' => 'api/contacts'], function() {
        // Find
        Route::get('/all', 'ContactsController@all')->middleware('permission:read-contacts');
        Route::get('/{id}', 'ContactsController@show')->middleware('permission:read-contacts');
    });
});
