<?php

use App\Http\Controllers\Api\V1\AppartementController;
use App\Http\Controllers\Api\V1\AuditController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ChargeController;
use App\Http\Controllers\Api\V1\ImmeubleController;
use App\Http\Controllers\Api\V1\ReclamationController;
use App\Http\Controllers\Api\V1\RecuController;
use App\Http\Controllers\Api\V1\ResidenceController;
use App\Http\Controllers\Api\V1\UserController;
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
        Route::get('/admin-only', function () {
            return response()->json([
                'success' => true,
                'message' => 'Accès administrateur autorisé.',
            ]);
        });

        Route::get('/users', [UserController::class, 'index']);
        Route::post('/users', [UserController::class, 'store']);
        Route::match(['put', 'patch'], '/users/{user}', [UserController::class, 'update']);
    });
    Route::middleware('auth:sanctum')->group(function () {
        Route::apiResource('residences', ResidenceController::class);
        Route::apiResource('residences.immeubles', ImmeubleController::class);
        Route::apiResource('immeubles.appartements', AppartementController::class);

        Route::put('/appartements/{appartement}/assign', [AppartementController::class, 'assign']);
        Route::delete('/appartements/{appartement}/assign', [AppartementController::class, 'deassign']);

        Route::apiResource('appartements.charges', ChargeController::class);
        Route::patch('/charges/{charge}/payer', [ChargeController::class, 'markAsPaid'])->name('charges.payer');
        Route::post('/charges/{charge}/recus', [RecuController::class, 'store'])->name('recus.store');
        Route::get('/recus/{recu}', [RecuController::class, 'show'])->name('recus.show');
        Route::get('/recus/{recu}/download', [RecuController::class, 'download'])->name('recus.download');
        Route::delete('/recus/{recu}', [RecuController::class, 'destroy'])->name('recus.destroy');

        Route::apiResource('reclamations', ReclamationController::class);

        Route::post('/reclamations/{reclamation}/analyser', [AuditController::class, 'trigger'])
            ->name('reclamations.analyser');
        Route::get('/reclamations/{reclamation}/audits', [AuditController::class, 'forReclamation'])
            ->name('reclamations.audits');
        Route::get('/audits', [AuditController::class, 'index'])->name('audits.index');
        Route::get('/audits/{audit}', [AuditController::class, 'show'])->name('audits.show');
    });
});
