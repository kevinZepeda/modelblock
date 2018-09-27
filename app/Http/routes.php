<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


Route::get('/stats/today', 'StatsController@index');
Route::get('/setup', 'SetupController@index');

Route::filter('admin', function ($route, $request) {
    if (is_object(Auth::user())) {
        if (!Auth::user()->isAdmin()) {
            return App::abort(401, 'You are not authorized.');
        }
    } else {
        return App::abort(401, 'You are not authorized.');
    }
});

Route::filter('manager', function ($route, $request) {
    if (is_object(Auth::user())) {
        if (!Auth::user()->canManage() && !Auth::user()->isAdmin()) {
            return App::abort(401, 'You are not authorized.');
        }
    } else {
        return App::abort(401, 'You are not authorized.');
    }
});

Route::filter('staff', function ($route, $request) {
    if (is_object(Auth::user())) {
        if (!Auth::user()->canManage() && !Auth::user()->isAdmin() && !Auth::user()->isStaff()) {
            return App::abort(401, 'You are not authorized.');
        }
    } else {
        return App::abort(401, 'You are not authorized.');
    }
});


Route::filter('client', function ($route, $request) {
    if (is_object(Auth::user())) {
        if (!Auth::user()->isClient()) {
            return App::abort(401, 'You are not authorized.');
        }
    } else {
        return App::abort(401, 'You are not authorized.');
    }
});

Route::filter('sale', function ($route, $request) {
    if (is_object(Auth::user())) {
        if (!Auth::user()->canManage() && !Auth::user()->isAdmin() && !Auth::user()->isClient()) {
            return App::abort(401, 'You are not authorized.');
        }
    } else {
        return App::abort(401, 'You are not authorized.');
    }
});


if (Config::get('app.single')) {
    Route::get('/auth/register', 'HomeController@index');
    Route::get('/auth/register', 'HomeController@index');
}

Route::get('/board/{board_url_hash?}', 'BoardController@index');
Route::get('/board/{board_query?}/{board_url_hash?}', 'BoardController@index');

Route::get('/settings/profile', 'SettingsController@profile');
Route::post('/settings/profile', 'SettingsController@profile');

Route::post('/api', 'ApiController@index');
Route::get('/api', 'ApiController@index');

Route::get('/quote/request/{quote_id?}', 'QuoteController@request');
Route::post('/quote/request/{quote_id?}', 'QuoteController@request');

Route::get('install', 'SetupController@install');
Route::post('install', 'SetupController@install');

Route::get('update', 'SetupController@install');
Route::post('update', 'SetupController@install');

Route::group(array('before' => array('auth|staff')), function () {

    if (Config::get('app.features.timesheets')) {

        Route::get('/home', 'HomeController@index');
        Route::post('/home', 'HomeController@index');

    } else if (Config::get('app.features.boards') && !Auth::guest()) {

        Route::get('/', 'BoardController@index');
        Route::post('/', 'BoardController@index');
        Route::get('/home', 'BoardController@index');
        Route::post('/home', 'BoardController@index');

    } else if (Config::get('app.features.projects') && !Auth::guest()) {

        Route::post('/', 'OfficeController@project');
        Route::get('/home', 'OfficeController@project');

    } else {
        if (!Auth::guest()) {
            Route::get('/', 'SettingsController@profile');
        }
        Route::post('/', 'SettingsController@profile');
        Route::post('/home', 'SettingsController@profile');
    }
    //Route::get('/auth/register', 'HomeController@index');

    Route::get('/settings/qunestionnaries', 'SettingsController@manageQuestionLists');
    Route::post('/settings/qunestionnaries', 'SettingsController@manageQuestionLists');

    Route::get('/settings/questionnarie/new', 'SettingsController@newQuestionnarie');
    Route::post('/settings/questionnarie/new', 'SettingsController@newQuestionnarie');

    Route::get('/settings/questionnarie/edit/{questionnarie_id?}', 'SettingsController@editQuestionnarie');
    Route::post('/settings/questionnarie/edit/{questionnarie_id?}', 'SettingsController@editQuestionnarie');

    Route::get('/settings/support', 'SettingsController@support');
    Route::post('/settings/support', 'SettingsController@support');

    Route::get('/settings/notifications', 'SettingsController@notifications');
    Route::post('/settings/notifications', 'SettingsController@notifications');

    Route::post('/office', 'OfficeController@index');
    Route::get('/office', 'OfficeController@index');

    Route::post('/office/project/{project_id?}', 'OfficeController@project');
    Route::get('/office/project/{project_id?}', 'OfficeController@project');

    Route::post('/office/project/files/{project_id?}', 'OfficeController@browse');
    Route::get('/office/project/files/{project_id?}', 'OfficeController@browse');

    Route::post('/office/project/file/download/{project_id?}', 'OfficeController@download');
    Route::get('/office/project/file/download/{project_id?}', 'OfficeController@download');

    Route::post('/office/project/workstream/{project_id?}', 'OfficeController@workstream');
    Route::get('/office/project/workstream/{project_id?}', 'OfficeController@workstream');

    Route::post('/office/project/backlog/{project_id?}', 'OfficeController@backlog');
    Route::get('/office/project/backlog/{project_id?}', 'OfficeController@backlog');

    Route::post('/office/project/requirements/{project_id?}', 'OfficeController@requirements');
    Route::get('/office/project/requirements/{project_id?}', 'OfficeController@requirements');

    Route::get('/office/project/{project_id?}/wiki/{page}', 'WikiController@showPage');
    Route::post('/office/project/{project_id?}/wiki/{page}', 'WikiController@showPage');

    Route::get('/office/project/{project_id?}/wiki/edit/{page}', 'WikiController@editPage');

    Route::get('/settings/notifications', 'SettingsController@notifications');

    Route::get('/quote/review/{quote_id?}', 'QuoteController@review');
    Route::post('/quote/review/{quote_id?}', 'QuoteController@review');

    Route::get('/quotes', 'QuoteController@pendingreview');
    Route::post('/quotes', 'QuoteController@pendingreview');

    Route::get('/settings/new-department', 'SettingsController@newDepartment');
    Route::post('/settings/new-department', 'SettingsController@newDepartment');

});

Route::group(array('before' => array('auth|admin')), function () {
    Route::get('/settings', 'SettingsController@index');
    Route::post('/settings', 'SettingsController@index');
});

Route::group(array('before' => array('auth|manager')), function () {
    Route::get('/settings/manage-board-templates', 'SettingsController@manageBoards');
    Route::post('/settings/manage-board-templates', 'SettingsController@manageBoards');

    Route::get('/settings/manage-users', 'SettingsController@manageUsers');
    Route::post('/settings/manage-users', 'SettingsController@manageUsers');

    Route::get('/settings/finance', 'SettingsController@finance');
    Route::post('/settings/finance', 'SettingsController@finance');

    Route::get('/settings/finance/category', 'SettingsController@category');
    Route::post('/settings/finance/category', 'SettingsController@category');

    Route::get('/settings/new-category', 'SettingsController@newCategory');
    Route::post('/settings/new-category', 'SettingsController@newCategory');

    Route::get('/settings/new-user', 'SettingsController@newUser');
    Route::post('/settings/new-user', 'SettingsController@newUser');

    Route::get('/settings/new-board-template', 'SettingsController@newBoard');
    Route::post('/settings/new-board-template', 'SettingsController@newBoard');

    Route::get('/office/finance', 'FinanceController@index');
    Route::post('/office/finance', 'FinanceController@index');

    Route::get('/office/finance/invoices', 'FinanceController@index');
    Route::post('/office/finance/invoices', 'FinanceController@index');

    Route::get('/office/finance/expenses', 'FinanceController@expenses');
    Route::post('/office/finance/expenses', 'FinanceController@expenses');

    Route::get('/office/finance/expense/{invoice_id?}', 'FinanceController@expense');
    Route::post('/office/finance/expense/{invoice_id?}', 'FinanceController@expense');

    Route::get('/office/finance/archive', 'FinanceController@archive');
    Route::post('/office/finance/archive', 'FinanceController@archive');

    Route::get('/office/finance/invoices/drafts', 'FinanceController@drafts');
    Route::post('/office/finance/invoices/drafts', 'FinanceController@drafts');

    Route::get('/office/finance/quotes', 'FinanceController@quotes');
    Route::post('/office/finance/quotes', 'FinanceController@quotes');

    Route::get('/office/finance/subscriptions', 'FinanceController@subscriptions');
    Route::post('/office/finance/subscriptions', 'FinanceController@subscriptions');

    Route::get('/office/finance/invoice/{invoice_id?}', 'FinanceController@invoice');
    Route::post('/office/finance/invoice/{invoice_id?}', 'FinanceController@invoice');

    Route::get('/office/finance/quote/{invoice_id?}', 'FinanceController@invoice');
    Route::post('/office/finance/quote/{invoice_id?}', 'FinanceController@invoice');

    Route::get('/office/finance/subscription/{invoice_id?}', 'FinanceController@invoice');
    Route::post('/office/finance/subscription/{invoice_id?}', 'FinanceController@invoice');

    Route::get('/invoice/reports', 'ReportsController@invoices');
    Route::post('/invoice/reports', 'ReportsController@invoices');

    Route::get('/office/customer/questionnarie/edit/{questionnarie_id?}', 'SettingsController@editQuestionnarie');
    Route::post('/office/customer/questionnarie/edit/{questionnarie_id?}', 'SettingsController@editQuestionnarie');

    Route::post('/office/customer/questionnaries/{customer_id?}', 'OfficeController@questionnaries');
    Route::get('/office/customer/questionnaries/{customer_id?}', 'OfficeController@questionnaries');

});

Route::group(array('before' => array('auth|sale')), function () {

    Route::post('/office/customer/{customer_id?}', 'OfficeController@customer');
    Route::get('/office/customer/{customer_id?}', 'OfficeController@customer');

    Route::get('/office/customer/new-user/{customer_id?}', 'OfficeController@newClientUser');
    Route::post('/office/customer/new-user/{customer_id?}', 'OfficeController@newClientUser');

    Route::get('/office/customer/users/{customer_id?}', 'OfficeController@listClientUsers');
    Route::post('/office/customer/users/{customer_id?}', 'OfficeController@listClientUsers');

});

Route::group(array('before' => array('auth|client')), function () {

    Route::post('/office/customer/invoices/{customer_id?}', 'OfficeController@customerInvoices');
    Route::get('/office/customer/invoices/{customer_id?}', 'OfficeController@customerInvoices');

    Route::post('/office/customer/quotes/{customer_id?}', 'OfficeController@customerQuotes');
    Route::get('/office/customer/quotes/{customer_id?}', 'OfficeController@customerQuotes');

});

if (Config::get('app.landing_page') == 'board' && Config::get('app.features.boards')) {
    Route::get('/{board_url_hash?}', 'BoardController@index');
    Route::post('/{board_url_hash?}', 'BoardController@index');
} else {
    Route::get('/', 'HomeController@index');
    Route::post('/', 'HomeController@index');
}

$this->app->bind('WikiController', function($app) {
    $repository = new WikipageRepository;
    $repository->setDatapath(base_path() . '/wiki');

    return new WikiController($repository);
});

Route::controllers([
    'auth' => 'Auth\AuthController',
    'password' => 'Auth\PasswordController'
]);