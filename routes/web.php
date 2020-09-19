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

Route::get('/', function() { return redirect('home'); });
Route::any('/home', 'PagesController@home') -> name('home');
Route::get('/ranking', 'PagesController@ranking') -> name('ranking');
Route::post('/ranking', 'PagesController@ranking') -> name('ranking');
Route::get('/contact', 'PagesController@contact') -> name('contact');
Route::get('/api/info', 'PagesController@api_info') -> name('api_info');
Route::get('/api/resources/diff_per_date', 
        'AtCoderDiffScoresApiController@diff_per_date') -> name('api_diff_per_date');
