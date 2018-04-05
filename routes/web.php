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
//Route::get('/home', 'HomeController@index')->name('home');
Route::get('/home', function () {
    return redirect('dashboard');
});

Auth::routes();

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return redirect('autoparts/sales');
        //return view('dashboard');
    });

    Route::get('/notifications', function () {
        return view('notifications');
    });
    Route::get('/notifications/markAsRead', 'NotificationController@markAsRead');

    require __DIR__ . '/autoparts/autoparts.php';
    require __DIR__ . '/contacts/contacts.php';
    require __DIR__ . '/events/events.php';
    require __DIR__ . '/files/files.php';
    require __DIR__ . '/modules/modules.php';
    require __DIR__ . '/news/news.php';
    require __DIR__ . '/profile/profile.php';
    require __DIR__ . '/tasks/tasks.php';
    require __DIR__ . '/users/users.php';
});
