<?php

//Manuel Login routes
Auth::routes();

Route::get('/user/setup','UserController@setup');

Route::post('/user/setup','UserController@save');

//Instagram Auth routes
Route::get('/login/instagram','Auth\InstagramController@index');

Route::get('/login/oauth','Auth\InstagramController@create');

Route::get('/', 'HomeController@index')->name('home');

//Admin Panel

Route::get('/admin','AdminController@index');

Route::post('/user/status','AdminController@updateStatus');

//My Photos

Route::get('/photos','PhotosController@index');

Route::post('/photos/upload','PhotosController@upload');

Route::post('/photos/remove','PhotosController@remove');
