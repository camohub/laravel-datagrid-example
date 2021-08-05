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
Route::get('/ajax', 'AjaxController@index')->name('ajax');
Route::get('/date-pickers', 'DatePickersController@index')->name('date-pickers');
Route::get('/documentation', 'DocumentationController@index')->name('documentation');


Route::get('/edit/{id}', 'ActionsController@edit')->name('edit');
Route::get('/visibility/{id}', 'ActionsController@visibility')->name('visibility');
Route::get('/delete/{id}', 'ActionsController@delete')->name('delete');
