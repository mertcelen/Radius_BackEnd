<?php

//Manuel Login routes
Auth::routes();

Route::get('/user/setup','UserController@setup');

//Instagram Auth routes
Route::get('/login/instagram','Auth\InstagramController@index');

Route::get('/login/oauth','Auth\InstagramController@create');

//Face Recognition Api
Route::get('/face/','FaceRecognitionController@apiCall');

Route::get('/', 'HomeController@index')->name('home');

//Admin Panel

Route::get('/admin','AdminController@index');

Route::post('/user/status','AdminController@updateStatus');