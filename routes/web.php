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

Route::get('/', 'Auth\LoginController@public_home_index');
Route::get('/home', 'HomeController@index')->name('home');
Route::resource('album', 'AlbumController');
Route::post('album/upload', 'AlbumController@upload')->name('album.upload');
Route::post('album/move/{album_id}', 'AlbumController@move')->name('album.move');
Route::resource('picture', 'PictureController');
