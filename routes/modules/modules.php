<?php

Route::group(['namespace' => 'Modules'], function() {
    // API Modules
    Route::group(['prefix' => 'api/modules'], function() {
        // Find
        Route::get('/permissions', 'ModuleController@permissions');
    });
});
