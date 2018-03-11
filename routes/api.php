<?php

//User Controller
Route::post('index','Api\UserController@index')->middleware('api');
Route::post('user/preferences','Api\UserController@preferences')->middleware('api:body_type,body_style');
Route::post('login','Api\UserController@login');
Route::post('register','Api\UserController@register');
Route::post('logout','Api\UserController@logout')->middleware('api');

//Image Controller
Route::post('user/favorites/add','Api\ImageController@addFavorite')->middleware('api:imageId');
Route::post('user/favorites/remove','Api\ImageController@removeFavorite')->middleware('api:imageId');
Route::post('user/favorites/list','Api\ImageController@getFavorites')->middleware('api');
Route::post('images/get','Api\ImageController@getImages')->middleware('api');
Route::post('images/add','Api\ImageController@addImage')->middleware('api');
Route::post('images/remove','Api\ImageController@removeImage')->middleware('api:imageId');

//Instagram Controller
Route::post('instagram/oauth','Api\InstagramController@create');
Route::get('instagram/url','Api\InstagramController@instagramUrl');
Route::get('instagram/retrieve','Api\InstagramController@retrieve')->middleware('api');

//FaceController
Route::post('face','Api\FaceController@face');
Route::post('test','Api\FaceController@test');

//Error Controller
Route::get('codes','Api\ErrorController@main');

//Admin Controller
Route::post('admin','Api\AdminController@index')->middleware(['api','admin']);
//Route::post('admin','Api\AdminController@index')->middleware(['api','admin']);