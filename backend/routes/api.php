<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ToolController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/tools', [ToolController::class, 'index']);
Route::get('/tools/{tool}/availability', [ToolController::class, 'availability']);
Route::get('/tools/{tool}', [ToolController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
});

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::post('/tools', [ToolController::class, 'store']);
    Route::patch('/tools/{tool}', [ToolController::class, 'update']);
    Route::delete('/tools/{tool}', [ToolController::class, 'destroy']);
});
