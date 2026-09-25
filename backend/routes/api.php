<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ToolController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/tools', [ToolController::class, 'index']);
Route::get('/tools/{rik}', [ToolController::class, 'show']);
Route::get('/categories', [ToolController::class, 'categories']);
Route::get('/tools/{rik}/availability', [ToolController::class, 'availability']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/my-orders', [OrderController::class, 'mine']);
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel']);
});

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::get('/orders', [OrderController::class, 'index']);
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus']);
    Route::post('/tools', [ToolController::class, 'store']);
    Route::patch('/tools/{rik}', [ToolController::class, 'update']);
    Route::delete('/tools/{rik}', [ToolController::class, 'destroy']);
});
