<?php
Route::group(['middleware' => ['parameters:secret']], function () {
    Route::post('index','Api\UserController@index');
    Route::post('logout','Api\UserController@logout');
    Route::post('user/favorites/list','Api\ImageController@getFavorites');
    Route::post('images/get','Api\ImageController@getImages');
    Route::post('images/add','Api\ImageController@addImage')->middleware('parameters:photo');
    Route::post('instagram/retrieve','Api\InstagramController@retrieve');
    Route::post('user/preferences','Api\UserController@preferences')->middleware('parameters:body_type,body_style');
    Route::post('user/favorites/add','Api\ImageController@addFavorite')->middleware('parameters:imageId');
    Route::post('user/favorites/remove','Api\ImageController@removeFavorite')->middleware('parameters:imageId');
    Route::post('images/remove','Api\ImageController@removeImage')->middleware('parameters:imageId');
});

Route::post('login','Api\UserController@login');
Route::post('register','Api\UserController@register');

Route::post('instagram/oauth','Api\InstagramController@create');
Route::get('instagram/url','Api\InstagramController@instagramUrl');

Route::get('codes','Api\ErrorController@main');

Route::group(['middleware' => ['admin','parameters:secret']],function(){
    Route::post('admin','Api\AdminController@index');
    Route::post('admin/user/status','Api\AdminController@updateStatus')->middleware('parameters:status,id');
    Route::post('admin/logs','Api\AdminController@logs');
});

Route::post('vision/magic','Api\VisionController@magic')->middleware(['session','parameters:imageId,type,part']);