<?php
Route::group(['middleware' => ['token']], function () {
    Route::post('index','Api\UserController@index');
    Route::post('user/preferences','Api\UserController@preferences');
    Route::post('logout','Api\UserController@logout');
    Route::post('user/favorites/add','Api\UserController@addFavorite');
    Route::post('user/favorites/remove','Api\UserController@removeFavorite');
    Route::post('user/favorites/list','Api\UserController@getFavorites');
    Route::post('images/get','Api\UserController@getImages');
    Route::post('images/add','Api\UserController@addImage');
    Route::post('images/remove','Api\UserController@removeImage');
    Route::post('face','Api\FaceController@face');
});
Route::get('codes','Api\ErrorController@main');

//Instagram Login API's
Route::post('instagram/oauth','Api\UserController@instagram');
Route::get('instagram/url','Api\UserController@instagramUrl');

//Standart login API's
Route::post('login','Api\UserController@login');
Route::post('register','Api\UserController@register');

