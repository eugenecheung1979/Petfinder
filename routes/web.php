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


Route::get('/', 'ViewController@index')->name('home');


Route::get('/showHistory/{animal}/{page}', 'ViewController@showHistory');

Route::get('/details/{animal}/{id}', 'ViewController@showDetails');

Route::get('/showMyFavorites', 'FavoriteController@show');

Route::post('/addToFavoriteList', 'FavoriteController@add');

Route::get('/showFavoritesMap', 'FavoriteController@showmap');

Route::post('/delFromFavoriteList', 'FavoriteController@delete');

Route::get('/searchView/{animal}', 'ViewController@showSearch');

Route::get('/getBreedlist/{animal}', 'ApiController@getBreed');

Route::get('/getConfig', 'ApiController@getConfig');

Route::get('/getLastSearch', 'ApiController@getLastSearch');

Route::post('/searchPet', 'ApiController@searchPet');

