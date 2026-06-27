<?php
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\Api\ConversationController;
use App\Http\Controllers\Api\DashboardController;
use Illuminate\Support\Facades\Route;
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::apiResource('tickets', TicketController::class);
    Route::get('tickets/{ticket}/conversations', [ConversationController::class, 'index']);
    Route::post('tickets/{ticket}/conversations', [ConversationController::class, 'store']);
    Route::get('/dashboard', [DashboardController::class, 'index']);
});
