<?php

Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');
Route::post('register', 'Auth\RegisterController@register');
Route::get('/', 'RoutingController@home')->middleware('web');

Route::group(['middleware' => ['auth','verification']], function () {
    // Route::get('/user/setup','UserController@setup');
    // Route::post('/user/setup','UserController@save');
    Route::get('/photos', 'RoutingController@photos')->middleware('session');
    Route::post('/photos/upload', 'Api\PhotoController@add')->middleware('session');
    Route::post('/photos/remove','Api\PhotoController@remove')->middleware('session');
    Route::post('/user/avatar', 'Api\UserController@userAvatar')->middleware('session');
    Route::post('/photos/remove', 'Api\PhotoController@remove')->middleware('session');
    Route::get('/settings', 'RoutingController@settings');
});
Route::get('/verify','RoutingController@verify');
Route::group(['middleware' => ['auth', 'session', 'admin']], function () {
    Route::get('/admin', 'RoutingController@admin');
    Route::get('/product','RoutingController@product');
    // Route::get('/admin/logs','Api\AdminController@logs');
    // Route::post('/user/status','AdminController@updateStatus');
});

//Instagram Auth routes
Route::get('/login/instagram', 'RoutingController@instagram');
Route::get('/login/oauth', 'Auth\InstagramController@create')->middleware('parameters:code');
