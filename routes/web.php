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
    return view('welcome');
    //return redirect('login');
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
    Route::group(['namespace' => 'Lists', 'prefix' => 'list'], function() {
        Route::get('/gender', 'ListGenderController@all');
        Route::get('/relationship', 'ListRelationshipController@all');
        Route::get('/contact', 'ListContactController@all');
        Route::get('/relation', 'ListRelationController@all');
        Route::get('/profession', 'ListProfessionController@all');
        Route::get('/position', 'ListPositionController@all');
        Route::get('/department', 'ListDepartmentController@all');
    });

    // Notifications
    Route::get('/notifications', 'NotificationController@index');

    // Users
    Route::group(['namespace' => 'Users'], function() {
        // Users
        Route::get('/users', 'UserController@index');
        Route::group(['prefix' => 'user'], function() {
            Route::get('/all', 'UserController@all');
            Route::post('/store', 'UserController@store');
            Route::put('/update/{id}', 'UserController@update');
            Route::put('/updatePassword/{id}', 'UserController@updatePassword');
            Route::delete('/destroy/{id}', 'UserController@destroy');
            Route::post('/upload/avatar/temp', 'UserController@uploadAvatarTemp');
            Route::post('/upload/avatar/{id}', 'UserController@uploadAvatar');
        });
        // Groups
        Route::get('groups', 'GroupController@index');
        Route::group(['prefix' => 'group'], function() {
            Route::get('/all', 'GroupController@all');
            Route::post('/store', 'GroupController@store');
            Route::put('/update/{id}', 'GroupController@update');
            Route::delete('/destroy/{id}', 'GroupController@destroy');
        });
    });

    // Profile
    Route::group(['namespace' => 'Profile', 'prefix' => 'profile'], function() {
        Route::get('/{id}', 'ProfileController@profile');
        Route::get('/{id}/edit', 'ProfileController@profileEdit');

        Route::put('/personal/update/{id}', 'ProfileController@updatePersonal');

        Route::post('/contact/store', 'ProfileController@storeContact');
        Route::delete('/contact/destroy/{id}', 'ProfileController@destroyContact');

        Route::post('/education/store', 'ProfileController@storeEducation');
        Route::delete('/education/destroy/{id}', 'ProfileController@destroyEducation');

        Route::post('/family/store', 'ProfileController@storeFamily');
        Route::delete('/family/destroy/{id}', 'ProfileController@destroyFamily');

        Route::post('/place/store', 'ProfileController@storePlace');
        Route::delete('/place/destroy/{id}', 'ProfileController@destroyPlace');

        Route::get('/{id}/work', 'ProfileController@work');
        Route::get('/{id}/work/edit', 'ProfileController@workEdit');
        Route::put('/work/update/{id}', 'ProfileController@updateWork');

        Route::get('/{id}/password/edit', 'ProfileController@password');
    });

    // News
    Route::group(['namespace' => 'News', 'prefix' => 'news'], function() {
        Route::get('/', 'NewsController@index');
        Route::get('/all', 'NewsController@all');
        Route::post('/store', 'NewsController@store');
        Route::delete('/destroy/{id}', 'NewsController@destroy');
        Route::post('/like/{id}', 'NewsController@like');
        Route::post('/upload/temp', 'NewsController@uploadTemp');
    });

    // Tasks
    Route::group(['namespace' => 'Tasks'], function() {
        Route::get('/tasks', 'TaskController@view');
        Route::group(['prefix' => 'task'], function() {
            Route::get('/byUser', 'TaskController@byUser');
            Route::post('/store', 'TaskController@store');
            Route::delete('/destroy/{id}', 'TaskController@destroy');
            Route::put('/markDone/{id}', 'TaskController@markDone');
            Route::put('/updateOrder', 'TaskController@updateOrder');
            Route::put('/editTask/{id}', 'TaskController@editTask');
        });
    });

    // Contacts
    Route::group(['namespace' => 'Contacts', 'prefix' => 'contacts'], function() {
        Route::get('/', 'ContactsController@index');
        Route::get('/all', 'ContactsController@all');
        Route::get('/{id}', 'ContactsController@show');
    });

    // Files
    Route::group(['namespace' => 'Files'], function() {
        Route::get('/files/{folderId?}', 'FileController@view');
        Route::group(['prefix' => 'file'], function() {
            Route::get('/byUser/{parentId?}', 'FileController@byUser');
            Route::post('/store', 'FileController@store');
            Route::put('/update/{id}', 'FileController@update');
            Route::delete('/destroy/{id}', 'FileController@destroy');
        });
    });

    // Events
    Route::group(['namespace' => 'Events'], function() {
        Route::get('/events', 'EventController@index');
        Route::group(['prefix' => 'event'], function() {
            Route::get('/all', 'EventController@all');
            Route::post('/store', 'EventController@store');
            Route::put('/update/{id}', 'EventController@update');
            Route::delete('/destroy/{id}', 'EventController@destroy');
        });
    });

});
