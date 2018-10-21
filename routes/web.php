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

Route::get('/', 'HomeController@index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/list', 'ListController@index');
Route::get('/map', 'MapController@index');
Route::get('/report', 'ReportController@index');
Route::post('/report', 'ReportController@store');
Route::post('/lookup/{long}/{lat}', 'LookupController@lookup');
Route::post('/vote/{type}', 'VoteController@vote');
Route::get('/image/{report}', 'ReportController@image');