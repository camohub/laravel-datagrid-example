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


Auth::routes();

Route::get('/', 'DefaultController@index')->name('index');
Route::get('/date-pickers', 'DefaultController@datePickers')->name('date-pickers');
Route::get('/ajax', 'DefaultController@ajax')->name('ajax');


Route::get('/edti', 'DefaultController@ajax')->name('edit');
Route::get('/visibility', 'DefaultController@ajax')->name('visibility');
Route::get('/delete', 'DefaultController@ajax')->name('delete');
