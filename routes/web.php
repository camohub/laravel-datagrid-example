<?php

use Illuminate\Support\Facades\Route;

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


//Auth::routes();

Route::get('/', 'DefaultController@index')->name('index');
Route::get('/date-pickers', 'DatePickersController@index')->name('date-pickers');
Route::get('/ajax', 'AjaxController@index')->name('ajax');
Route::get('/ajax-datagrid', 'AjaxController@ajaxDatagrid')->name('ajax-datagrid');


Route::get('/edit', 'ActionsController@ajax')->name('edit');
Route::get('/visibility', 'ActionsController@ajax')->name('visibility');
Route::get('/delete', 'ActionsController@ajax')->name('delete');
