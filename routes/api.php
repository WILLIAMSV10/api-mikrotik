<?php

use App\Http\Controllers\AddressController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MikrotikController;
use App\Http\Controllers\QueueController;
use App\Http\Controllers\UserController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/mikrotik/interfaces', [MikrotikController::class, 'getRouterData']);

//Rutas para Users
Route::get('/mikrotik/users', [UserController::class, 'getUsers'])->name('mikrotik.user.list');
Route::get('/mikrotik/user/{id}/edit', [UserController::class, 'editUser'])->name('mikrotik.user.edit');
Route::get('/mikrotik/user/new', [UserController::class, 'create'])->name('mikrotik.user.create');
Route::post('/mikrotik/user/store', [UserController::class, 'store'])->name('mikrotik.user.store');
Route::put('/mikrotik/user/{id}', [UserController::class, 'update'])->name('mikrotik.user.update');
Route::get('/mikrotik/user/{id}', [UserController::class, 'delete'])->name('mikrotik.user.delete');


//Rutas para ip addresses
Route::get('/mikrotik/address', [AddressController::class, 'get'])->name('mikrotik.address.list');
Route::get('/mikrotik/address/new', [AddressController::class, 'create'])->name('mikrotik.address.create');
Route::get('/mikrotik/address/{id}/edit', [AddressController::class, 'edit'])->name('mikrotik.address.edit');
Route::put('/mikrotik/address/{id}', [AddressController::class, 'update'])->name('mikrotik.address.update');
Route::post('/mikrotik/address/store', [AddressController::class, 'store'])->name('mikrotik.address.store');
Route::get('/mikrotik/address/{id}', [AddressController::class, 'delete'])->name('mikrotik.address.delete');


//Rutas para ancho de banda
Route::get('/mikrotik/queue', [QueueController::class, 'get'])->name('mikrotik.queue.list');
Route::get('/mikrotik/queue/new', [QueueController::class, 'create'])->name('mikrotik.queue.create');
Route::get('/mikrotik/queue/{id}/edit', [QueueController::class, 'edit'])->name('mikrotik.queue.edit');
Route::post('/mikrotik/queue/store', [QueueController::class, 'store'])->name('mikrotik.queue.store');
Route::put('/mikrotik/queue/{id}', [QueueController::class, 'update'])->name('mikrotik.queue.update');
Route::get('/mikrotik/queue/{id}', [QueueController::class, 'delete'])->name('mikrotik.queue.delete');
