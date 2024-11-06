<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MikrotikController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/mikrotik/interfaces', [MikrotikController::class, 'getRouterData']);

Route::get('/mikrotik/users', [MikrotikController::class, 'getUsers']);
