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

    // Autoparts
    Route::group(['namespace' => 'Autoparts'], function() {
        Route::group(['prefix' => 'autoparts'], function() {
            Route::get('/' , function () {
                return view('autoparts.autoparts');
            })->middleware('permission:read-autoparts');
            Route::get('/config' , function () {
                return view('autoparts.config');
            })->middleware('permission:create-autoparts');
            Route::get('/{id}' , function ($id) {
                return view('autoparts.autopart', ['id' => $id]);
            })->middleware('permission:read-autoparts');
        });
        Route::group(['prefix' => 'api/autoparts'], function() {
            Route::get('/all', 'AutopartController@all')->middleware('permission:read-autoparts');
            Route::post('/filter', 'AutopartController@filter')->middleware('permission:read-autoparts');
            Route::get('/show/{id}', 'AutopartController@show')->middleware('permission:read-autoparts');
            Route::post('/store', 'AutopartController@store')->middleware('permission:create-autoparts');
            Route::put('/update/{id}', 'AutopartController@update')->middleware('permission:update-autoparts');
            Route::delete('/destroy/{id}', 'AutopartController@destroy')->middleware('permission:delete-autoparts');
            // Images
            Route::post('/images/upload/temp', 'AutopartController@uploadImageTemp')->middleware('permission:create-autoparts');
            Route::post('/images/upload/', 'AutopartController@uploadImage')->middleware('permission:update-autoparts');
            Route::post('/images/sort/{autopartId}', 'AutopartController@sortImage')->middleware('permission:update-autoparts');
            Route::delete('/images/destroy/{id}', 'AutopartController@destroyImage')->middleware('permission:delete-autoparts');
            // Lists
            Route::group(['prefix' => 'list'], function() {
                Route::get('/makes', 'AutopartController@makes')->middleware('permission:read-autoparts');
                Route::get('/makesFull', 'AutopartController@makesFull')->middleware('permission:read-autoparts');
                Route::post('/makes/store', 'AutopartController@storeMake')->middleware('permission:create-autoparts');
                Route::delete('/makes/destroy/{id}', 'AutopartController@destroyMake')->middleware('permission:delete-autoparts');
                Route::put('/makes/order', 'AutopartController@orderMake')->middleware('permission:update-autoparts');
                Route::get('/models', 'AutopartController@models')->middleware('permission:read-autoparts');
                Route::post('/models/store', 'AutopartController@storeModel')->middleware('permission:create-autoparts');
                Route::delete('/models/destroy/{id}', 'AutopartController@destroyModel')->middleware('permission:delete-autoparts');
                Route::put('/models/order', 'AutopartController@orderModel')->middleware('permission:update-autoparts');
                Route::get('/origins', 'AutopartController@origins')->middleware('permission:read-autoparts');
                Route::get('/status', 'AutopartController@status')->middleware('permission:read-autoparts');
                Route::get('/years', 'AutopartController@years')->middleware('permission:read-autoparts');
                Route::post('/years/store', 'AutopartController@storeYear')->middleware('permission:create-autoparts');
                Route::delete('/years/destroy/{id}', 'AutopartController@destroyYear')->middleware('permission:delete-autoparts');
                Route::put('/years/order', 'AutopartController@orderYear')->middleware('permission:update-autoparts');
            });
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

    // Files
    Route::group(['namespace' => 'Files'], function() {
        Route::group(['prefix' => 'files'], function() {
            Route::get('/' , function () {
                return view('files.files');
            })->middleware('permission:read-files');
            Route::get('/folder/{folderId?}' , function () {
                return view('files.files');
            })->middleware('permission:read-files');
        });
        Route::group(['prefix' => 'api/files'], function() {
            Route::get('/byCreator/{parentId?}', 'FileController@byCreator')->middleware('permission:read-files');
            Route::post('/store', 'FileController@store')->middleware('permission:create-files');
            Route::put('/update/{id}', 'FileController@update')->middleware('permission:update-files');
            Route::delete('/destroy/{id}', 'FileController@destroy')->middleware('permission:delete-files');
        });
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

    // Profile
    Route::group(['namespace' => 'Profile'], function() {
        Route::group(['prefix' => 'profile'], function() {
            Route::get('/{id}', 'ProfileController@profile')->middleware('permission:read-profile');
            Route::get('/{id}/edit', 'ProfileController@profileEdit')->middleware('permission:update-profile');

            Route::get('/{id}/work', 'ProfileController@work');
            Route::get('/{id}/work/edit', 'ProfileController@workEdit');

            Route::get('/{id}/password/edit', 'ProfileController@password');
        });
        Route::group(['prefix' => 'api/profile'], function() {
            Route::put('/personal/update/{id}', 'ProfileController@updatePersonal');

            Route::post('/contact/store', 'ProfileController@storeContact');
            Route::delete('/contact/destroy/{id}', 'ProfileController@destroyContact');

            Route::post('/education/store', 'ProfileController@storeEducation');
            Route::delete('/education/destroy/{id}', 'ProfileController@destroyEducation');

            Route::post('/family/store', 'ProfileController@storeFamily');
            Route::delete('/family/destroy/{id}', 'ProfileController@destroyFamily');

            Route::post('/place/store', 'ProfileController@storePlace');
            Route::delete('/place/destroy/{id}', 'ProfileController@destroyPlace');

            Route::put('/work/update/{id}', 'ProfileController@updateWork');
        });
    });

    // Profile Lists
    Route::group(['namespace' => 'Profile', 'prefix' => 'api/list/profile'], function() {
        Route::get('/gender', 'ProfileListController@gender');
        Route::get('/relationship', 'ProfileListController@relationship');
        Route::get('/contact', 'ProfileListController@contact');
        Route::get('/relation', 'ProfileListController@relation');
        Route::get('/profession', 'ProfileListController@profession');
        Route::get('/position', 'ProfileListController@position');
        Route::get('/department', 'ProfileListController@department');
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

    // Users
    Route::group(['namespace' => 'Users'], function() {
        // Users
        Route::group(['prefix' => 'users'], function() {
            Route::get('/' , function () {
                return view('users.users');
            })->middleware('permission:read-users');
        });
        Route::group(['prefix' => 'api/users'], function() {
            Route::get('/all', 'UserController@all')->middleware('permission:read-users');
            Route::get('/filter', 'UserController@filter')->middleware('permission:read-users');
            Route::post('/store', 'UserController@store')->middleware('permission:create-users');
            Route::put('/update/{id}', 'UserController@update')->middleware('permission:update-users');
            Route::put('/updatePassword/{id}', 'UserController@updatePassword')->middleware('permission:update-users');
            Route::delete('/destroy/{id}', 'UserController@destroy')->middleware('permission:delete-users');
            Route::post('/upload/avatar/temp', 'UserController@uploadAvatarTemp')->middleware('permission:update-users');
            Route::post('/upload/avatar', 'UserController@uploadAvatar')->middleware('permission:update-users');
        });
        // Roles
        Route::group(['prefix' => 'roles'], function() {
            Route::get('/' , function () {
                return view('users.roles');
            })->middleware('permission:read-roles');
        });
        Route::group(['prefix' => 'api/roles'], function() {
            Route::get('/all', 'RoleController@all')->middleware('permission:read-roles');
            Route::get('/filter', 'RoleController@filter')->middleware('permission:read-roles');
            Route::get('/{id}', 'RoleController@show')->middleware('permission:read-roles');
            Route::post('/store', 'RoleController@store')->middleware('permission:create-roles');
            Route::put('/update/{id}', 'RoleController@update')->middleware('permission:update-roles');
            Route::delete('/destroy/{id}', 'RoleController@destroy')->middleware('permission:delete-roles');
        });
        // Permissions
        Route::group(['prefix' => 'api/permissions'], function() {
            Route::get('/all', 'PermissionController@all');
        });
    });

});
