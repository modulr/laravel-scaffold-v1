<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    //return view('welcome');
    return redirect('login');
});
Route::get('/home', function () {
    return redirect('dashboard');
});

Auth::routes();

Route::middleware('auth')->group(function () {
    //Route::get('/home', 'HomeController@index')->name('home');

    // Dashboard
    Route::get('/dashboard', 'DashboardController@index');
    Route::get('/dashboard/markAsRead', 'DashboardController@markAsRead');

    // Notifications
    Route::get('/notifications', 'NotificationController@index');

    // News
    Route::get('/news', 'NewsController@index');
    Route::get('/news/all', 'NewsController@all');
    Route::post('/news/store', 'NewsController@store');
    Route::delete('/news/destroy/{id}', 'NewsController@destroy');
    Route::post('/news/like/{id}', 'NewsController@like');
    Route::post('/news/upload/temp', 'NewsController@uploadTemp');

    // Tasks
    Route::get('/tasks', 'TaskController@index');
    Route::get('/task/{id}', 'TaskController@byUser');

    // Contacts
    Route::get('/contacts', 'ContactsController@index');
    Route::get('/contacts/all', 'ContactsController@all');

    // Files
    Route::get('/file/byUser/{parentId?}', 'FileController@byUser');
    Route::post('/file/store', 'FileController@store');
    Route::put('/file/update/{id}', 'FileController@update');
    Route::delete('/file/destroy/{id}', 'FileController@destroy');
    Route::get('/files/{folderId?}', 'FileController@view');

});
