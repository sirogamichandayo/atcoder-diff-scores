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

if (version_compare(PHP_VERSION, '7.2.0', '>=')) {
    error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
}

Route::get('/home', 'PagesController@home') -> name('home');
Route::post('/home', 'PagesController@user') -> name('show');
Route::get('/ranking', 'PagesController@ranking') -> name('ranking');
Route::get('/contact', 'PagesController@contact') -> name('contact');
