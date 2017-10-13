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
    });

    // Roles
    Route::group(['namespace' => 'Users', 'prefix' => 'roles'], function() {
        Route::get('/', 'RoleController@index');
        Route::get('/all', 'RoleController@all');
        Route::post('/store', 'RoleController@store');
        Route::put('/update/{id}', 'RoleController@update');
        Route::delete('/destroy/{id}', 'RoleController@destroy');
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
    Route::get('/opportunities/{id}/get', 'OpportunityController@show');
    Route::get('/opportunities/all', 'OpportunityController@all');
    Route::post('/opportunities/store', 'OpportunityController@store');
    Route::put('/opportunities/update/{id}', 'OpportunityController@update');
    Route::delete('/opportunities/destroy/{id}', 'OpportunityController@destroy');
    Route::get('/opportunities/list/priorities', 'OpportunityController@listPriorities');
    Route::get('/opportunities/list/clients', 'OpportunityController@listClients');
    Route::get('/opportunities/list/customers', 'OpportunityController@listCustomers');
    Route::get('/opportunities/list/areas', 'OpportunityController@listAreas');

    //Employes
    Route::get('/employees', 'EmployeeController@index');
    Route::get('/employees/all', 'EmployeeController@all');
    Route::post('/employees/store', 'EmployeeController@store');
    Route::put('/employees/update/{id}', 'EmployeeController@update');
    Route::delete('/employees/destroy/{id}', 'EmployeeController@destroy');
    Route::get('/employees/sellers', 'EmployeeController@sellers');
    Route::get('/employees/designers', 'EmployeeController@designers');

    //Quotes
    Route::get('/quote', 'QuoteController@index');
    Route::get('/quote/all', 'QuoteController@all');
    Route::get('/quote/{id}', 'QuoteController@show');
    Route::post('/quote/store', 'QuoteController@store');
    Route::put('/quote/update/{id}', 'QuoteController@update');
    Route::delete('/quote/destroy/{id}', 'QuoteController@destroy');

    //Attachments
    Route::post('/attachments/store', 'AttachmentController@store');

    //Serivces
    Route::get('/services/all', 'ListTypeServiceController@all');

    // Customers
    Route::get('/customers', 'CustomerController@index');
    Route::get('/customers/all', 'CustomerController@all');
    Route::post('/customers/store', 'CustomerController@store');
    Route::put('/customers/update/{id}', 'CustomerController@update');
    Route::delete('/customers/destroy/{id}', 'CustomerController@destroy');
    Route::get('/customers/list/clients/{id}', 'CustomerController@listClients');

    // Clients
    Route::get('/clients', 'ClientController@index');
    Route::get('/clients/all', 'ClientController@all');
    Route::post('/clients/store', 'ClientController@store');
    Route::put('/clients/update/{id}', 'ClientController@update');
    Route::delete('/clients/destroy/{id}', 'ClientController@destroy');

    // Widgets
    Route::get('/widget/getMonthOpportunities', 'WidgetController@getMonthOpportunities');
});
