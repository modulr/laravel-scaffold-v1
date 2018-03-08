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
//Route::get('/home', 'HomeController@index')->name('home');
Route::get('/home', function () {
    return redirect('dashboard');
});

Auth::routes();

// Students
Route::group(['namespace' => 'Students'], function() {
    Route::group(['prefix' => 'api/students'], function() {
        Route::post('/register', 'StudentController@register');
        // Lists
        Route::group(['prefix' => 'lists'], function() {
            Route::get('/certificates', 'StudentController@certificates');
            Route::get('/genders', 'StudentController@genders');
            Route::get('/paymentMethods', 'StudentController@paymentMethods');
            Route::get('/positions', 'StudentController@positions');
            Route::get('/studies', 'StudentController@studies');
            Route::get('/states', 'StudentController@states');
            Route::get('/cities/{stateId}', 'StudentController@cities');
            Route::get('/stores/{cityId}', 'StudentController@stores');
        });
    });
});

Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard' , function () {
        //return view('dashboard');
        return redirect('students');
    });

    // Notifications
    Route::get('/notifications' , function () {
        return view('notifications');
    });
    Route::get('/notifications/markAsRead', 'NotificationController@markAsRead');

    // Students
    Route::group(['namespace' => 'Students'], function() {
        Route::group(['prefix' => 'students'], function() {
            Route::get('/' , function () {
                return view('students.list');
            });
            Route::get('/{id}' , function ($id) {
                return view('students.edit', ['id' => $id]);
            });
        });
        Route::group(['prefix' => 'api/students'], function() {
            Route::get('/all', 'StudentController@all');
            Route::get('/show/{id}', 'StudentController@show');
            Route::put('/update/{id}', 'StudentController@update');
            Route::get('/export', 'StudentController@export');
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
