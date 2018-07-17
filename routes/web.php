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

Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', 'DashboardController@index');
    Route::get('/dashboard/markAsRead', 'DashboardController@markAsRead');

    // Notifications
    Route::get('/notifications', 'NotificationController@index');

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
        Route::post('/api/users/upload/avatar', 'UserController@uploadAvatar2')->middleware('permission:update-users');
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

    require __DIR__ . '/profile/profile.php';

    // News
    Route::group(['namespace' => 'News', 'prefix' => 'news'], function() {
        Route::get('/', 'NewsController@index')->middleware('permission:read-news');
        Route::get('/all', 'NewsController@all')->middleware('permission:read-news');
        Route::post('/store', 'NewsController@store')->middleware('permission:create-news');
        Route::delete('/destroy/{id}', 'NewsController@destroy')->middleware('permission:delete-news');
        Route::post('/like/{id}', 'NewsController@like')->middleware('permission:read-news');
        Route::post('/upload/temp', 'NewsController@uploadTemp')->middleware('permission:create-news');
    });

    // Tasks
    Route::group(['namespace' => 'Tasks', 'prefix' => 'tasks'], function() {
        Route::get('/', 'TaskController@index')->middleware('permission:read-tasks');
        Route::get('/byUser', 'TaskController@byUser')->middleware('permission:read-tasks');
        Route::post('/store', 'TaskController@store')->middleware('permission:create-tasks');
        //Route::put('/update/{id}', 'TaskController@update')->middleware('permission:update-tasks');
        Route::delete('/destroy/{id}', 'TaskController@destroy')->middleware('permission:delete-tasks');
        Route::put('/markDone/{id}', 'TaskController@markDone')->middleware('permission:update-tasks');
        Route::put('/updateOrder', 'TaskController@updateOrder')->middleware('permission:update-tasks');
    });

    // Contacts
    Route::group(['namespace' => 'Contacts', 'prefix' => 'contacts'], function() {
        Route::get('/', 'ContactsController@index')->middleware('permission:read-contacts');
        Route::get('/all', 'ContactsController@all')->middleware('permission:read-contacts');
        Route::get('/{id}', 'ContactsController@show')->middleware('permission:read-contacts');
    });

    // Files
    Route::group(['namespace' => 'Files', 'prefix' => 'files'], function() {
        Route::get('/', 'FileController@index')->middleware('permission:read-files');
        Route::get('/folder/{folderId?}', 'FileController@index')->middleware('permission:read-files');
        Route::get('/byUser/{parentId?}', 'FileController@byUser')->middleware('permission:read-files');
        Route::post('/store', 'FileController@store')->middleware('permission:create-files');
        Route::put('/update/{id}', 'FileController@update')->middleware('permission:update-files');
        Route::delete('/destroy/{id}', 'FileController@destroy')->middleware('permission:delete-files');
    });

    // Events
    Route::group(['namespace' => 'Events', 'prefix' => 'events'], function() {
        Route::post('/images/upload/temp', 'EventController@uploadImageTemp')->middleware('permission:create-news');
        Route::post('/images/upload/', 'EventController@uploadImage')->middleware('permission:update-news');
        Route::post('/images/sort/{eventId}', 'EventController@sortImage')->middleware('permission:update-news');
        Route::delete('/images/destroy/{id}', 'EventController@destroyImage')->middleware('permission:delete-events');
    });

    Route::post('/paypal/checkout', 'PaypalController@getCheckout');
    Route::get('/paypal/status', 'PaypalController@getStatus');
});

// Vistas de Platillos
Route::group(['namespace' => 'Platillos'], function() {
    Route::get('/platillos', 'PlatillosController@index')->middleware('permission:read-events');
    Route::get('/platillos/{id}', 'PlatillosController@show')->middleware('permission:read-events');

    Route::get('/cocina/platillos', 'CocinaPlatillosController@index')->middleware('auth', 'permission:read-events');
    Route::get('/cocina/platillos/menu-del-dia', 'CocinaPlatillosController@meduDelDia')->middleware('auth', 'permission:read-events');
    Route::get('/cocina/platillos/{id}/reservaciones', 'CocinaPlatillosController@reservaciones')->middleware('auth', 'permission:read-events');
});

// Events
Route::group(['namespace' => 'Events'], function() {
    Route::get('/events', 'EventController@index');
    Route::get('/events/{event}', 'EventController@show');
});

Route::middleware('auth')->group(['namespace' => 'Events'], function() {
    Route::get('/user/events', 'UserEventsController@index')->middleware('permission:read-events');
    Route::post('/user/events', 'UserEventsController@store')->middleware('permission:create-events');
    Route::get('/user/events/reserved', 'UserEventsController@reserved')->middleware('permission:read-events');
    
    Route::put('/events/{event}', 'EventController@update')->middleware('permission:update-events');
    Route::delete('/events/{event}', 'EventController@destroy')->middleware('permission:delete-events');
    
    Route::post('/published-events', 'PublishedEventsController@store')->middleware('permission:update-events');
    Route::delete('/published-events', 'PublishedEventsController@destroy')->middleware('permission:update-events');

    Route::post('/events/images/upload/temp', 'EventImagesController@uploadImageTemp')->middleware('permission:create-news');
    Route::post('/events/images/upload/', 'EventImagesController@uploadImage')->middleware('permission:update-news');
    Route::post('/events/images/sort/{eventId}', 'EventImagesController@sortImage')->middleware('permission:update-news');
    Route::delete('/events/images/destroy/{id}', 'EventImagesController@destroyImage')->middleware('permission:delete-events');
});

Route::middleware('auth')->group(['namespace' => 'Reservations'], function() {
    Route::get('/reservations', 'ReservationsController@index')->middleware('permission:read-events');
    Route::post('/reservations', 'ReservationsController@store')->middleware('permission:read-events');
    Route::delete('/reservations/{id}', 'ReservationsController@destroy')->middleware('permission:read-events');

    Route::post('/approved-reservations', 'ApprovedReservationsController@store')->middleware('permission:update-events');
    Route::delete('/approved-reservations', 'ApprovedReservationsController@destroy')->middleware('permission:update-events');
});
