<?php

use App\Http\Controllers\ProductoController;
use App\Http\Controllers\SolicitudController;
use App\Http\Middleware\EnsureJson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::group(['prefix' => '/producto', 'middleware' => EnsureJson::class,], function () {
        Route::get('/', [ProductoController::class, 'index']);
        Route::post('/', [ProductoController::class, 'store']);
        Route::get('/{id}', [ProductoController::class, 'show']);
        Route::put('/', [ProductoController::class, 'update']);
        Route::delete('/{id}', [ProductoController::class, 'destroy']);
    });

    Route::group(['prefix' => '/solicitud', 'middleware' => EnsureJson::class,], function () {
        Route::get('/', [SolicitudController::class, 'index']);
        Route::post('/', [SolicitudController::class, 'store']);
        Route::get('/{id}', [SolicitudController::class, 'show']);
        Route::put('/', [SolicitudController::class, 'update']);
        Route::delete('/{id}', [SolicitudController::class, 'destroy']);
    });
});
