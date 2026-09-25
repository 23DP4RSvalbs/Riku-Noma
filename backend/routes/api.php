<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ToolController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/tools', [ToolController::class, 'index']);
Route::get('/tools/{rik}', [ToolController::class, 'show']);
Route::get('/categories', [ToolController::class, 'categories']);

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::post('/tools', [ToolController::class, 'store']);
    Route::patch('/tools/{rik}', [ToolController::class, 'update']);
    Route::delete('/tools/{rik}', [ToolController::class, 'destroy']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
});
