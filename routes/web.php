<?php

Route::get('/', 'WebController@frontend');
Route::post('/account/logout', 'Auth\LoginController@logout')->name('logout');

Route::prefix('account')->middleware('guest')->group(function () {
    // account route
    Route::get('/', 'Auth\LoginController@showLoginForm')->name('login');
    Route::post('login', 'Auth\LoginController@login');

    // Route::get('/register', 'Auth\RegisterController@showRegistrationForm')->name('register');
    // Route::get('/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
    // Route::get('/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');

    Route::post('/register', 'Auth\RegisterController@register');
    Route::post('/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::post('/reset', 'Auth\ResetPasswordController@reset')->name('password.update');
});

Route::prefix('email')->middleware('auth')->group(function () {
    Route::post('/resend', 'Auth\VerificationController@resend')->name('verification.resend')->middleware('throttle:6,1');
    Route::get('/verify/{id}', 'Auth\VerificationController@verify')->name('verification.verify')->middleware(['signed', 'throttle:6,1']);
    // Route::get('/verify', 'Auth\VerificationController@show')->name('verification.notice');
});

Route::prefix('backend')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', 'WebController@backend');
});
