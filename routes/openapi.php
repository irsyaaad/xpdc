<?php

use Illuminate\Support\Facades\Route;

use OpenApi\CekTarifController;
use OpenApi\CekResiController;
use OpenApi\AnterajaController;
use App\Http\Controllers\OpenApi\OpenApiAuthController;

Route::middleware('basic.auth')->group(function () {
    Route::apiResource('cek-tarif', CekTarifController::class);
    Route::apiResource('cek-resi', CekResiController::class);
    Route::apiResource('anteraja', AnterajaController::class);
});
