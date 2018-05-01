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

    //Favorites Routes
    Route::get('user/favorites', 'Api\ImageController@getFavorites');
    Route::post('user/favorites', 'Api\ImageController@addFavorite')->middleware('parameters:productId');
    Route::post('user/favorites/delete', 'Api\ImageController@removeFavorite')->middleware('parameters:favoriteId');

    //Recommendation Values Routes
    Route::get('user/values','Api\UserController@getValues');
    Route::post('user/values','Api\UserController@values')->middleware('parameters:first,second');

    //Image Routes
    Route::get('image', 'Api\ImageController@get');
    Route::post('image', 'Api\ImageController@add')->middleware('parameters:image');
    Route::post('image/remove', 'Api\ImageController@remove')->middleware('parameters:imageId');

    //User Avatar Routes
    Route::get('user/avatar', 'Api\UserController@getAvatar');
    Route::post('user/avatar', 'Api\UserController@userAvatar')->middleware('parameters:image');

    Route::get('instagram', 'Api\InstagramController@get');
    Route::post('user/password', 'Api\UserController@password')->middleware('parameters:old-password,new-password,new-password2');

    //Admin Routes
    Route::post('admin', 'Api\AdminController@index')->middleware('admin');
    Route::post('admin/user/status', 'Api\AdminController@updateStatus')->middleware(['parameters:secret,status,id', 'admin']);

    //Product Routes
    Route::get('product', 'Api\ProductController@get')->middleware('parameters:type,color');
    Route::post('product', 'Api\ProductController@add')->middleware('parameters:brand,color,image,link,type,gender');

    //User Style Route
    Route::post('user/style','Api\UserController@setup')->middleware('parameters:selected');
});

//Faagram Routes
Route::post('faagram/user', 'Faagram\UserController@add');
Route::get('faagram/user', 'Faagram\UserController@get');
Route::delete('faagram/user', 'Faagram\UserController@remove');

//Tampermonkey Routes
Route::post('dummy/set','RoutingController@setData');
Route::post('dummy/get','RoutingController@getData');
Route::get('dummy/categories','RoutingController@getCategories');