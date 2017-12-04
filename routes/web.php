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
    // Dashboard
    Route::get('/dashboard' , function () {
        return view('dashboard');
    });

    // Notifications
    Route::get('/notifications' , function () {
        return view('notifications');
    });
    Route::get('/notifications/markAsRead', 'NotificationController@markAsRead');

    // Lists
    Route::group(['namespace' => 'Profile', 'prefix' => 'list'], function() {
        Route::get('/gender', 'ProfileListController@gender');
        Route::get('/relationship', 'ProfileListController@relationship');
        Route::get('/contact', 'ProfileListController@contact');
        Route::get('/relation', 'ProfileListController@relation');
        Route::get('/profession', 'ProfileListController@profession');
        Route::get('/position', 'ProfileListController@position');
        Route::get('/department', 'ProfileListController@department');
    });

    // Users
    Route::group(['namespace' => 'Users'], function() {
        // Users
        Route::group(['prefix' => 'users'], function() {
            Route::get('/', 'UserController@index')->middleware('permission:read-users');
            Route::get('/all', 'UserController@all')->middleware('permission:read-users');
            Route::post('/store', 'UserController@store')->middleware('permission:create-users');
            Route::put('/update/{id}', 'UserController@update')->middleware('permission:update-users');
            Route::put('/updatePassword/{id}', 'UserController@updatePassword')->middleware('permission:update-users');
            Route::delete('/destroy/{id}', 'UserController@destroy')->middleware('permission:delete-users');
            Route::post('/upload/avatar/temp', 'UserController@uploadAvatarTemp')->middleware('permission:update-users');
            Route::post('/upload/avatar/{id}', 'UserController@uploadAvatar')->middleware('permission:update-users');
        });
        // Roles
        Route::group(['prefix' => 'roles'], function() {
            Route::get('/', 'RoleController@index')->middleware('permission:read-roles');
            Route::get('/all', 'RoleController@all')->middleware('permission:read-roles');
            Route::get('/{id}', 'RoleController@show')->middleware('permission:read-roles');
            Route::post('/store', 'RoleController@store')->middleware('permission:create-roles');
            Route::put('/update/{id}', 'RoleController@update')->middleware('permission:update-roles');
            Route::delete('/destroy/{id}', 'RoleController@destroy')->middleware('permission:delete-roles');
        });
        // Permissions
        Route::group(['prefix' => 'permissions'], function() {
            Route::get('/all', 'PermissionController@all');
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
    Route::group(['namespace' => 'News'], function() {
        Route::group(['prefix' => 'news'], function() {
            Route::get('/' , function () {
                return view('news.news');
            })->middleware('permission:read-news');
            Route::get('/{id}' , function ($id) {
                return view('news.item', ['id' => $id]);
            })->middleware('permission:read-news');
        });
        Route::group(['prefix' => 'api/news'], function() {
            Route::get('/all', 'NewsController@all')->middleware('permission:read-news');
            Route::get('/show/{id}', 'NewsController@show')->middleware('permission:read-news');
            Route::post('/store', 'NewsController@store')->middleware('permission:create-news');
            Route::delete('/destroy/{id}', 'NewsController@destroy')->middleware('permission:delete-news');
            Route::post('/like/{id}', 'NewsController@like')->middleware('permission:read-news');
            Route::post('/upload/temp', 'NewsController@uploadTemp')->middleware('permission:create-news');
        });
    });

    // Tasks
    Route::group(['namespace' => 'Tasks'], function() {
        Route::group(['prefix' => 'tasks'], function() {
            Route::get('/' , function () {
                return view('tasks.tasks');
            })->middleware('permission:read-tasks');
        });
        Route::group(['prefix' => 'api/tasks'], function() {
            Route::get('/byCreator', 'TaskController@byCreator')->middleware('permission:read-tasks');
            Route::post('/store', 'TaskController@store')->middleware('permission:create-tasks');
            //Route::put('/update/{id}', 'TaskController@update')->middleware('permission:update-tasks');
            Route::delete('/destroy/{id}', 'TaskController@destroy')->middleware('permission:delete-tasks');
            Route::put('/markDone/{id}', 'TaskController@markDone')->middleware('permission:update-tasks');
            Route::put('/updateOrder', 'TaskController@updateOrder')->middleware('permission:update-tasks');
        });
    });

    // Contacts
    Route::group(['namespace' => 'Contacts'], function() {
        Route::group(['prefix' => 'contacts'], function() {
            Route::get('/' , function () {
                return view('contacts.contacts');
            })->middleware('permission:read-contacts');
        });
        Route::group(['prefix' => 'api/contacts'], function() {
            Route::get('/all', 'ContactsController@all')->middleware('permission:read-contacts');
            Route::get('/{id}', 'ContactsController@show')->middleware('permission:read-contacts');
        });
    });

    // Files
    Route::group(['namespace' => 'Files', 'prefix' => 'files'], function() {
        Route::get('/', 'FileController@index')->middleware('permission:read-files');
        Route::get('/folder/{folderId?}', 'FileController@index')->middleware('permission:read-files');
        Route::get('/byOwner/{parentId?}', 'FileController@byOwner')->middleware('permission:read-files');
        Route::post('/store', 'FileController@store')->middleware('permission:create-files');
        Route::put('/update/{id}', 'FileController@update')->middleware('permission:update-files');
        Route::delete('/destroy/{id}', 'FileController@destroy')->middleware('permission:delete-files');
    });

    // Events
    Route::group(['namespace' => 'Events'], function() {
        Route::group(['prefix' => 'events'], function() {
            Route::get('/' , function () {
                return view('events.events');
            })->middleware('permission:read-events');
            Route::get('/{id}' , function ($id) {
                return view('events.event', ['id' => $id]);
            })->middleware('permission:read-events');
        });
        Route::group(['prefix' => 'api/events'], function() {
            Route::get('/all', 'EventController@all')->middleware('permission:read-events');
            Route::get('/byCreator', 'EventController@byCreator')->middleware('permission:read-events');
            Route::get('/show/{id}', 'EventController@show')->middleware('permission:read-events');
            Route::post('/store', 'EventController@store')->middleware('permission:create-events');
            Route::put('/update/{id}', 'EventController@update')->middleware('permission:update-events');
            Route::delete('/destroy/{id}', 'EventController@destroy')->middleware('permission:delete-events');
            // Images
            Route::post('/images/upload/temp', 'EventController@uploadImageTemp')->middleware('permission:create-news');
            Route::post('/images/upload/', 'EventController@uploadImage')->middleware('permission:update-news');
            Route::post('/images/sort/{eventId}', 'EventController@sortImage')->middleware('permission:update-news');
            Route::delete('/images/destroy/{id}', 'EventController@destroyImage')->middleware('permission:delete-events');
        });
    });

});
