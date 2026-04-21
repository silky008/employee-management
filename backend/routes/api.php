<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\AuditLogController;

Route::get('/health', function () {
    return response()->json(['status' => 'OK',
        'message'                         => 'API is working']);
});
Route::get('/clients', function () {
    return 'CLIENT ROUTE HIT';
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store'])->middleware('can:isAdmin');
    Route::put('/users/{user}', [UserController::class, 'update']);
    Route::delete('/users/{user}', [UserController::class, 'destroy']);
    Route::get('/audit-logs', [AuditLogController::class, 'index']);
    Route::get('/clients', [ClientController::class, 'index']);
    Route::post('/clients', [ClientController::class, 'store'])->middleware('can:isAdmin');
    Route::put('/clients/{client}', [ClientController::class, 'update']);
    Route::delete('/clients/{client}', [ClientController::class, 'destroy']);
    Route::get('/clients/{client}', [ClientController::class, 'show']);
    Route::get('/me', function (Request $request) {
        return $request->user()->load('role');
    });

    Route::get('/dashboard-stats', [DashboardController::class, 'stats'])
        ->middleware('auth:sanctum');
});
