<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MikrotikController;
use App\Http\Controllers\UserController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/mikrotik/interfaces', [MikrotikController::class, 'getRouterData']);

Route::get('/mikrotik/users', [UserController::class, 'getUsers']);

Route::get('/mikrotik/user/{id}/edit', [UserController::class, 'editUser'])->name('mikrotik.user.edit');
