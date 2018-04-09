<?php
Route::group(['middleware' => ['parameters:secret']], function () {
    Route::post('index','Api\UserController@index');
    Route::post('logout','Api\UserController@logout');
    Route::post('user/favorites/list','Api\PhotoController@getFavorites');
    Route::post('user/favorites/add','Api\PhotoController@addFavorite')->middleware('parameters:imageId');
    Route::post('user/favorites/remove','Api\PhotoController@removeFavorite')->middleware('parameters:imageId');
    Route::post('images/get','Api\PhotoController@get');
    Route::post('images/add','Api\PhotoController@add')->middleware('parameters:photo');
    Route::post('images/remove','Api\PhotoController@remove')->middleware('parameters:imageId');
    Route::post('instagram/get','Api\InstagramController@get');
    Route::post('user/preferences','Api\UserController@preferences')->middleware('parameters:body_type,body_style');
    Route::post('user/password','Api\UserController@password')->middleware('parameters:old-password,new-password,new-password2');
    Route::post('magic','Api\VisionController@detect');
    Route::post('admin','Api\AdminController@index')->middleware('admin');
    Route::post('admin/user/status','Api\AdminController@updateStatus')->middleware(['admin','parameters:status,id']);
    Route::post('admin/logs','Api\AdminController@logs')->middleware('admin');
    Route::post('user/avatar/get','Api\UserController@getAvatar');
});
Route::post('user/avatar','Api\UserController@userAvatar')->middleware('parameters:secret,photo');
Route::get('verify','Api\UserController@verify');
Route::post('login','Api\UserController@login')->middleware('parameters:email,password');
Route::post('register','Api\UserController@register')->middleware('parameters:email,name,password');
Route::post('instagram/oauth','Api\InstagramController@create')->middleware('parameters:code');

Route::get('instagram/url','Api\InstagramController@instagramUrl');
Route::get('codes','Api\ErrorController@main');

Route::get('products','Api\ProductController@main');
Route::get('product','Api\ProductController@get');
Route::post('product','Api\ProductController@add');