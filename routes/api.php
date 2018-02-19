<?php

//Standart login API's

Route::post('login','Api\UserController@login');

Route::post('register','Api\UserController@register');

Route::post('index','Api\UserController@index');

Route::post('user/preferences','Api\UserController@preferences');

Route::post('logout','Api\UserController@logout');

//Instagram Login API's
Route::get('instagram/url','Api\UserController@instagramUrl');

Route::post('instagram/oauth','Api\UserController@instagram');

//Face Recognition API
Route::get('face/','Api\FaceDetectionController@main');

//Favorites API

Route::post('user/favorites/add','Api\UserController@addFavorite');
Route::post('user/favorites/remove','Api\UserController@removeFavorite');
Route::post('user/favorites/list','Api\UserController@getFavorites');