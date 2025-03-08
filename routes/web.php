<?php

use App\Http\Controllers\Dashboard\AepsController;
use App\Http\Controllers\Dashboard\ProviderController;
use App\Http\Controllers\Dashboard\RoleController;
use App\Http\Controllers\Dashboard\SchemesController;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Controllers\Dashboard\ApiController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'user'], function () {
    Route::post('register', [UserController::class, 'register'])->name('register');
    Route::post('login', [UserController::class, 'login'])->name('login');
    Route::get('logout', [UserController::class, 'logout'])->name('logout');
    Route::post('verifyotp', [UserController::class, 'verifyotp'])->name('verifyotp');
    Route::get('profile', [UserController::class, 'profile'])->name('profile');
});

Route::group(['prefix' => 'apidata'], function () {
    Route::get('get', [ApiController::class, 'get'])->name('get');
    Route::get('getAll', [ApiController::class, 'getAll'])->name('getAll');
    Route::post('add_and_update', [ApiController::class, 'add_and_update'])->name('add_and_update');
    Route::get('apicommission/{option1}/{option2}', [ApiController::class, 'apicommission'])->name('apicommission');
    Route::post('apicommissionupdate', [ApiController::class, 'apicommissionupdate'])->name('apicommissionupdate');
});

Route::group(['prefix' => 'provider'], function () {
    Route::get('get', [ProviderController::class, 'get'])->name('get');
    Route::post('add_and_update', [ProviderController::class, 'add_and_update'])->name('add_and_update');
});


Route::group(['prefix' => 'scheme'], function () {
    Route::get('get', [SchemesController::class, 'get'])->name('get');
    Route::post('addupdate', [SchemesController::class, 'addupdate'])->name('addupdate');
    Route::post('remove/{id}', [SchemesController::class, 'remove'])->name('remove');
    Route::get('schemecommission/{option1}/{option2}', [SchemesController::class, 'schemecommission'])->name('schemecommission');
    Route::post('schemecommissionupdate', [SchemesController::class, 'schemecommissionupdate'])->name('schemecommissionupdate');
});

Route::group(['prefix' => 'usertype'], function () {
    Route::get('get', [RoleController::class, 'get'])->name('get');
    Route::post('addupdate', [RoleController::class, 'addupdate'])->name('addupdate');
});


Route::group(['prefix' => 'aeps'], function () {
    Route::get('onboarding', [AepsController::class, 'onboarding'])->name('onboarding');
});



Route::get('{any}', function () {
    return view('app');
})->where('any', '.*');