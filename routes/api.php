<?php

Route::middleware('auth:api')->group(function () {
    Route::get('/user', 'Apis\UserController@index');
    Route::get('/roles', 'Apis\RoleController@index');
    Route::resource('users', 'Apis\UsersController');
    Route::resource('apps', 'Apis\AppsController');
});
