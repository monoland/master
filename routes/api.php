<?php

Route::middleware('throttle:200,1')->group(function () {
    Route::post('media', 'Apis\MediaController@store');
    Route::delete('media/{media}', 'Apis\MediaController@destroy');
    Route::get('media/{media}/download', 'Apis\MediaController@download');
});

Route::middleware('auth:api')->group(function () {
    Route::get('/user', 'Apis\UserController@index');
    Route::get('/roles', 'Apis\RoleController@index');
    Route::resource('users', 'Apis\UsersController');
    Route::resource('apps', 'Apis\AppsController');
});
