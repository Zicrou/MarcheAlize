<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\Admin\ProduitController;
use App\Http\Controllers\Api\v1\Admin\TypeController;
use App\Http\Controllers\Api\v1\Admin\JournalController;
use App\Http\Controllers\Api\v1\DepenseController;
use App\Http\Controllers\Api\v1\OperartionController;
use App\Http\Controllers\Api\v1\EquipeController;

$idRegex   = '[0-9a-z\-]+';

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->name('api.v1.')->group(function (){
    Route::middleware('auth:sanctum')->group(function () {
        
        // Produits
        Route::resource('produit', ProduitController::class)->except(['show']);
        Route::get('supprime-photo-produit/{id}', [ProduitController::class, 'destroyImage'])->name("produit.destroyImage");
        // Types
        Route::resource('type', TypeController::class)->except(['show']);
        
        // Journal
        Route::get('journal', [JournalController::class, 'index'])->name('journal.index');
        Route::get('journal/depenses', [JournalController::class, 'journalDepense'])->name('journal.depenses');
        Route::get('journal/operations', [JournalController::class, 'journalOperation'])->name('journal.operations');
        
        // Dépenses
        Route::resource('depense', DepenseController::class);
        
        // Equipe
        Route::get('/equipe', [EquipeController::class, 'index'])->name('equipe.index');
        Route::delete('/equipe/{id}', [EquipeController::class, 'destroy'])->name('equipe.destroy');
        Route::post('/equipe/accepter-invitation', [EquipeController::class, 'accepterInvitation'])->name('equipe.accepterInvitation');
        Route::post('/equipe/invite', [EquipeController::class, 'invite'])->name('equipe.invite');


        });
    Route::post('/register', [\App\Http\Controllers\Api\v1\AuthController::class, 'register']);
    Route::post('/login', [\App\Http\Controllers\Api\v1\AuthController::class, 'login']);
    Route::post('/logout', [\App\Http\Controllers\Api\v1\AuthController::class, 'logout'])->middleware('auth:sanctum');
});