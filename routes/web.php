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

Route::pattern('id', '\d+');

Route::get('/', 'OrdersController@index');
Route::resource('orders', 'OrdersController', ['except' => ['show']]);
Route::resource('clients', 'ClientsController');
Route::resource('products', 'ProductsController');
Route::group(['prefix' => 'reporting'], function () {
    Route::get('/', 'ReportingController@index')->name('reporting.index');
    Route::get('pdf/{month}/{route}/{action?}', 'ReportingController@pdf')->name('reporting.pdf');
});

Route::get('clients/{id}/pdf/{action?}', 'ClientsController@pdf')->name('clients.pdf');
Route::get('orders/ajax/client/{id?}', 'OrdersController@ajaxProductPrice');
Route::get('orders/all', ['as' => 'orders.all', 'uses' => 'OrdersController@all']);