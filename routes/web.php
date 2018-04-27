<?php

//Manual Login Auth Routes
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');
Route::post('register', 'Auth\RegisterController@register');

//Instagram Auth routes
Route::get('/login/instagram', 'RoutingController@instagram');
Route::get('/login/oauth', 'Auth\InstagramController@create')->middleware('parameters:code');

//Home Route
Route::get('/', 'RoutingController@welcome');

//Email Verification
Route::get('/setup/email', 'RoutingController@verify');

//Faagram Data Generation
Route::get('faagram/zafer', 'Faagram\AssociateController@fake');

//Setup Pages
Route::group(['middleware' => ['auth']], function () {
    Route::get('/setup/style', 'UserController@setup');
});

Route::group(['middleware' => ['auth', 'setup']], function () {
    Route::get('/home','RoutingController@home');
    Route::get('/photos', 'RoutingController@photos')->middleware('session');
    Route::post('/photos/upload', 'Api\ImageController@add')->middleware('session');
    Route::post('/photos/remove', 'Api\ImageController@remove')->middleware('session');
    Route::post('/user/avatar', 'Api\UserController@userAvatar')->middleware('session');
    Route::post('/photos/remove', 'Api\ImageController@remove')->middleware('session');
    Route::get('/settings', 'RoutingController@settings');
});

Route::group(['middleware' => ['auth', 'session', 'admin']], function () {
    Route::get('/admin/users', 'RoutingController@admin');
    Route::get('/product/add', 'RoutingController@productAdd');
    Route::get('/product/list', 'RoutingController@productList');
    Route::get('/faagram/users', 'RoutingController@faagramUsers');
    Route::get('/faagram/posts', 'RoutingController@faagramPosts');
    Route::get('/faagram/relations', 'RoutingController@faagramRelations');
});