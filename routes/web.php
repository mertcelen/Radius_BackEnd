<?php

//Manuel Login routes
Auth::routes();

Route::group(['middleware' => ['auth']], function () {
	// Route::get('/user/setup','UserController@setup');
	// Route::post('/user/setup','UserController@save');
	Route::get('/','RoutingController@home')->middleware('session');
	Route::get('/photos','RoutingController@upload');
	// Route::post('/photos/upload','Api\PhotosController@upload');
	// Route::post('/photos/remove','Api\PhotosController@remove');
});

Route::group(['middleware' => ['auth','session','admin']] ,function(){
    Route::get('/admin','RoutingController@admin');
    // Route::get('/admin/logs','Api\AdminController@logs');
    // Route::post('/user/status','AdminController@updateStatus');
});

//Instagram Auth routes
Route::get('/login/instagram','Auth\InstagramController@index');
Route::get('/login/oauth','Auth\InstagramController@create');
