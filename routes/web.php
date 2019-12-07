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

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('blocos','BlocoController');

Route::resource('servidores','ServidorController');

Route::resource('coordenadores','CoordenadorController');

Route::resource('users','UserController');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
