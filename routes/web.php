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

Route::get('', 'PagesController@home') -> name('home');
Route::post('', 'PagesController@user') -> name('user');
Route::get('/ranking', 'PagesController@ranking') -> name('ranking');
Route::get('/about', 'PagesController@about') -> name('about');
Route::get('/contact', 'PagesController@contact') -> name('contact');
