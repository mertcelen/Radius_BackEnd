<?php

//Standart login API's
Route::post('login','Api\UserController@login');

Route::post('register','Api\UserController@register');

Route::post('index','Api\UserController@index');

Route::post('user/preferences','Api\UserController@preferences');

//Instagram Login API's
Route::get('instagram/url','Api\UserController@instagramUrl');

Route::post('instagram/oauth','Api\UserController@instagram');

//Face Recognition API
Route::get('face/','Api\FaceDetectionController@main');