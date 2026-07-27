<?php

use App\Http\Controllers\Api\V1\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    Route::get('/health', function () {
        return response()->json([
            'success' => true,
            'message' => 'SyndicFlow API is running.',
            'data' => [
                'status' => 'healthy',
                'version' => 'v1',
            ],
        ]);
    });

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function (): void {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });
    Route::middleware(['auth:sanctum', 'role:admin'])->group(function (): void {
    Route::get('/admin/test', function () {
        return response()->json([
            'success' => true,
            'message' => 'Accès administrateur autorisé.',
        ]);
    });
});
});
