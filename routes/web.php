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


    // Lists
    Route::group(['namespace' => 'Lists'], function() {
        Route::get('/list/gender', 'ListGenderController@all');
        Route::get('/list/relationship', 'ListRelationshipController@all');
    });

    // Notifications
    Route::get('/notifications', 'NotificationController@index');

    // Users
    Route::get('/users', 'UserController@index');
    Route::get('/user/all', 'UserController@all');
    Route::post('/user/store', 'UserController@store');
    Route::put('/user/update/{id}', 'UserController@update');
    Route::delete('/user/destroy/{id}', 'UserController@destroy');
    Route::post('/user/upload/avatar/temp', 'UserController@uploadAvatarTemp');
    Route::post('/user/upload/avatar/{id}', 'UserController@uploadAvatar');

    // Profile
    Route::group(['namespace' => 'Profile'], function() {
        Route::get('/profile/{id}', 'ProfileController@information');
        Route::get('/profile/notifications/{id}', 'ProfileController@notifications');
        Route::get('/profile/work/{id}', 'ProfileController@work');
        Route::put('/profile/personal/update/{id}', 'ProfileController@updatePersonal');
    });

    // News
    Route::get('/news', 'NewsController@index');
    Route::get('/news/all', 'NewsController@all');
    Route::post('/news/store', 'NewsController@store');
    Route::delete('/news/destroy/{id}', 'NewsController@destroy');
    Route::post('/news/like/{id}', 'NewsController@like');
    Route::post('/news/upload/temp', 'NewsController@uploadTemp');

    //Tasks
    Route::get('/tasks', 'TaskController@view');
    Route::get('/task/byUser', 'TaskController@byUser');
    Route::post('/task/store', 'TaskController@store');
    Route::delete('/task/destroy/{id}', 'TaskController@destroy');
    Route::put('/task/markDone/{id}', 'TaskController@markDone');
    Route::put('/task/updateOrder', 'TaskController@updateOrder');
    Route::put('/task/editTask/{id}', 'TaskController@editTask');

    // Contacts
    Route::get('/contacts', 'ContactsController@index');
    Route::get('/contacts/all', 'ContactsController@all');

    // Files
    Route::get('/file/byUser/{parentId?}', 'FileController@byUser');
    Route::post('/file/store', 'FileController@store');
    Route::put('/file/update/{id}', 'FileController@update');
    Route::delete('/file/destroy/{id}', 'FileController@destroy');
    Route::get('/files/{folderId?}', 'FileController@view');

    // Projects and Opportunities
    Route::get('/opportunities', 'OpportunityController@index');
    Route::get('/opportunities/all', 'OpportunityController@all');
    Route::post('/opportunities/store', 'OpportunityController@store');
    Route::put('/opportunities/update/{id}', 'OpportunityController@update');
    Route::delete('/opportunities/destroy/{id}', 'OpportunityController@destroy');
    Route::get('/opportunities/list/priorities', 'OpportunityController@listPriorities');
    Route::get('/opportunities/list/contacts', 'OpportunityController@listContacts');

    //Employes
    Route::get('/employees/sellers', 'EmployeeController@sellers');
    Route::get('/employees/designers', 'EmployeeController@designers');

    //Quotes
    Route::get('/quote', 'QuoteController@index');
    Route::get('/quote/all', 'QuoteController@all');
    Route::get('/quote/{id}', 'QuoteController@show');
    Route::post('/quote/store', 'QuoteController@store');
    Route::put('/quote/update/{id}', 'QuoteController@update');
    Route::delete('/quote/destroy/{id}', 'QuoteController@destroy');

    // Customers
    Route::get('/customers', 'CustomerController@index');
    Route::get('/customers/all', 'CustomerController@all');
    Route::post('/customers/store', 'CustomerController@store');
    Route::put('/customers/update/{id}', 'CustomerController@update');
    Route::delete('/customers/destroy/{id}', 'CustomerController@destroy');
    Route::get('/customers/list/contacts/{id}', 'CustomerController@listContacts');
});
