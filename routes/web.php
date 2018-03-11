<?php

//Manuel Login routes
Auth::routes();

Route::group(['middleware' => ['auth']], function () {
	Route::get('/user/setup','UserController@setup');
	Route::post('/user/setup','UserController@save');
	Route::get('/','HomeController@index')->name('home');
	Route::get('/photos','PhotosController@index');
	Route::post('/photos/upload','PhotosController@upload');
	Route::post('/photos/remove','PhotosController@remove');
});

Route::group(['middleware' => ['auth','session','admin']] ,function(){
    Route::get('/admin','Api\AdminController@index');
    Route::get('/admin/logs','Api\AdminController@logs');
    Route::post('/user/status','AdminController@updateStatus');
});

//Instagram Auth routes
Route::get('/login/instagram','Auth\InstagramController@index');
Route::get('/login/oauth','Auth\InstagramController@create');
