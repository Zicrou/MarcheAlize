<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\Admin\ProduitController;
use App\Http\Controllers\Api\v1\Admin\TypeController;
use App\Http\Controllers\Api\v1\Admin\JournalController;
use App\Http\Controllers\Api\v1\DepenseController;
use App\Http\Controllers\Api\v1\OperartionController;
use App\Http\Controllers\Api\v1\EquipeController
;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->name('api.v1.')->group(function (){
    Route::middleware('auth:sanctum')->group(function () {
        Route::resource('produit', ProduitController::class)->except(['show']);
        Route::resource('type', TypeController::class)->except(['show']);
        Route::get('journal', [JournalController::class, 'index'])->name('journal.index');
        Route::get('journal/depenses', [JournalController::class, 'journalDepense'])->name('journal.depenses');
        Route::get('journal/operations', [JournalController::class, 'journalOperation'])->name('journal.operations');
        Route::resource('depense', DepenseController::class);

        });
    Route::post('/register', [\App\Http\Controllers\Api\v1\AuthController::class, 'register']);
    Route::post('/login', [\App\Http\Controllers\Api\v1\AuthController::class, 'login']);
    Route::post('/logout', [\App\Http\Controllers\Api\v1\AuthController::class, 'logout'])->middleware('auth:sanctum');
});