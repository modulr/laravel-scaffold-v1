<?php

Route::group(['namespace' => 'Stb'], function() {
    // Views
    Route::get('/reservaciones' , function () {
        return view('stb.reservations'); })->middleware('permission:read-events');

    Route::get('/mis_reservaciones' , function () {
        return view('stb.myreservations'); })->middleware('permission:read-events');

    // CRUD
    Route::group(['prefix' => 'reservation'], function() {
        Route::get('/reservations', 'ReservationController@reservations')->middleware('permission:read-events');
        Route::get('/reserve/{id}/{quantity}', 'ReservationController@reserve')->middleware('permission:update-events');
        Route::get('/cancel/{id}', 'ReservationController@cancel')->middleware('permission:update-events');

        Route::get('/myReservations', 'ReservationController@myReservations')->middleware('permission:read-events');
        Route::get('/approve/{id}', 'ReservationController@approve')->middleware('permission:update-events');
        Route::get('/reject/{id}', 'ReservationController@reject')->middleware('permission:update-events');
    });
});
