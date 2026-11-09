<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\Admin\ProduitController;
use App\Http\Controllers\Api\v1\Admin\TypeController;
use App\Http\Controllers\Api\v1\Admin\JournalController;
use App\Http\Controllers\Api\v1\DepenseController;
use App\Http\Controllers\Api\v1\OperationController;
use App\Http\Controllers\Api\v1\EquipeController;

$idRegex   = '[0-9a-z\-]+';

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->name('api.v1.')->group(function (){
    Route::middleware('auth:sanctum')->group(function () {
        
        // Produits
        Route::resource('produits', ProduitController::class)->except(['show']);
        Route::get('supprime-photo-produit/{id}', [ProduitController::class, 'destroyImage'])->name("produit.destroyImage");
        // Types
        Route::resource('types', TypeController::class)->except(['show']);
        
        // Opérations
        Route::resource('operations', OperationController::class);
       
        // Journaux
        Route::get('journal', [JournalController::class, 'index'])->name('journal.index');
        Route::get('journal/depenses', [JournalController::class, 'journalDepense'])->name('journal.depenses');
        Route::get('journal/operations', [JournalController::class, 'journalOperation'])->name('journal.operations');
        
        // Dépenses
        Route::resource('depenses', DepenseController::class);
        
        // Equipe
        Route::get('/equipes', [EquipeController::class, 'index'])->name('equipe.index');
        Route::delete('/equipes/{id}', [EquipeController::class, 'destroy'])->name('equipe.destroy');
        Route::post('/equipes/accepter-invitation', [EquipeController::class, 'accepterInvitation'])->name('equipe.accepterInvitation');
        Route::post('/equipes/invite', [EquipeController::class, 'invite'])->name('equipe.invite');


        });
    Route::post('/register', [\App\Http\Controllers\Api\v1\AuthController::class, 'register']);
    Route::post('/login', [\App\Http\Controllers\Api\v1\AuthController::class, 'login']);
    Route::post('/logout', [\App\Http\Controllers\Api\v1\AuthController::class, 'logout'])->middleware('auth:sanctum');
});