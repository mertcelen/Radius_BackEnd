<?php
//Manuel Login Routes
Route::post('login', 'Api\UserController@login')->middleware('parameters:email,password');
Route::post('register', 'Api\UserController@register')->middleware('parameters:email,name,password');
//Instagram Login Routes
Route::post('instagram/oauth', 'Api\InstagramController@create')->middleware('parameters:code');
Route::get('instagram/url', 'Api\InstagramController@instagramUrl');

//Api Code Route
Route::get('codes', 'Api\ErrorController@main');

Route::group(['middleware' => ['parameters:secret']], function () {
    Route::post('index', 'Api\UserController@index');
    Route::post('logout', 'Api\UserController@logout');
    Route::post('user/favorites/list', 'Api\ImageController@getFavorites');
    Route::post('user/favorites/add', 'Api\ImageController@addFavorite')->middleware('parameters:imageId');
    Route::post('user/favorites/remove', 'Api\ImageController@removeFavorite')->middleware('parameters:imageId');
    Route::post('images/get', 'Api\ImageController@get');
    Route::post('images/add', 'Api\ImageController@add')->middleware('parameters:photo');
    Route::post('images/remove', 'Api\ImageController@remove')->middleware('parameters:imageId');
    Route::post('instagram/get', 'Api\InstagramController@get');
    Route::post('user/preferences', 'Api\UserController@preferences')->middleware('parameters:body_type,body_style');
    Route::post('user/password', 'Api\UserController@password')->middleware('parameters:old-password,new-password,new-password2');
    Route::post('admin', 'Api\AdminController@index')->middleware('admin');
    Route::post('admin/user/status', 'Api\AdminController@updateStatus')->middleware(['admin', 'parameters:status,id']);
    Route::post('admin/logs', 'Api\AdminController@logs')->middleware('admin');
    Route::post('user/avatar/get', 'Api\UserController@getAvatar');
    Route::post('/user/values','Api\UserController@values')->middleware('parameters:first,second,third');
    Route::get('/user/values','Api\UserController@getValues');
    Route::post('user/avatar', 'Api\UserController@userAvatar')->middleware('parameters:photo');
    Route::post('product', 'Api\ProductController@add')->middleware('parameters:brand,color,image,link,type,gender');
    Route::get('products', 'Api\ProductController@main');
    Route::get('product', 'Api\ProductController@get');
});

Route::post('dummy/set','RoutingController@setData');
Route::post('dummy/get','RoutingController@getData');
Route::get('dummy/categories','RoutingController@getCategories');

Route::post('faagram/user/add', 'Faagram\UserController@add');
Route::get('faagram/user/get', 'Faagram\UserController@get');
Route::post('faagram/user/remove', 'Faagram\UserController@remove');

Route::get('faagram/user/real','Faagram\AssociateController@temp');