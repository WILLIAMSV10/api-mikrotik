<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MikrotikController;
use App\Http\Controllers\UserController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/mikrotik/interfaces', [MikrotikController::class, 'getRouterData']);

Route::get('/mikrotik/users', [UserController::class, 'getUsers'])->name('mikrotik.users');

Route::get('/mikrotik/user/{id}/edit', [UserController::class, 'editUser'])->name('mikrotik.user.edit');

Route::get('/mikrotik/user/new', [UserController::class, 'create'])->name('mikrotik.user.create');

Route::post('/mikrotik/user/store', [UserController::class, 'store'])->name('mikrotik.user.store');

Route::put('/mikrotik/user/{id}', [UserController::class, 'update'])->name('mikrotik.user.update');
