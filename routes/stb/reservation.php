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
        Route::get('/reserve/{saucerId}/{quantity}', 'ReservationController@reserve')->middleware('permission:update-events');
        Route::get('/cancel/{reservationId}', 'ReservationController@cancel')->middleware('permission:update-events');

        Route::get('/myReservations', 'ReservationController@myReservations')->middleware('permission:read-events');
        Route::get('/approve/{reservationId}', 'ReservationController@approve')->middleware('permission:update-events');
        Route::get('/reject/{reservationId}', 'ReservationController@reject')->middleware('permission:update-events');
    });
});
