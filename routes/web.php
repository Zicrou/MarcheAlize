<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProduitController;
use App\Http\Controllers\Admin\JournalController;
use App\Http\Controllers\Admin\TypeController;
use App\Http\Controllers\DepenseController;
use App\Http\Controllers\OperationController;
use App\Http\Controllers\EquipeController;


$idRegex   = '[0-9]+';
$slugRegex = '[0-9a-z\-]+';
$membre   = '[0-9a-z\-]+';

Route::get('/', function () {
    return view('base');
});

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function (){
    Route::resource('produit', ProduitController::class)->except(['show']);
    Route::resource('type', TypeController::class)->except(['show']);
    Route::get('journal', [JournalController::class, 'index'])->name('journal.index');
    Route::get('journal/depenses', [JournalController::class, 'journalDepense'])->name('journal.depenses');
    Route::get('journal/operations', [JournalController::class, 'journalOperation'])->name('journal.operations');
});
Route::get('produit/{id}', [ProduitController::class, 'destroyImage'])
    ->name('produit.destroyImage')
    ->where([
        'id' => $idRegex,
])->middleware(['auth', 'verified']);

Route::middleware('auth')->name('depense.')->group(function (){
    Route::resource('depense', DepenseController::class);
});

// Route::prefix('operation')->name('operation.')->group(function (){
    Route::resource('operation', OperationController::class)->middleware(['auth', 'verified']);
// });

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/equipe', [EquipeController::class, 'index'])->name('equipe.index');
    Route::post('/equipe', [EquipeController::class, 'store'])->name('equipe.store');
    Route::delete('/equipe/{membre}', [EquipeController::class, 'destroy'])->name('equipe.destroy');
    Route::post('/equipe/accepter-invitation', [EquipeController::class, 'accepterInvitation'])->name('equipe.accepterInvitation');
    Route::post('/equipe/invite', [EquipeController::class, 'invite'])->name('equipe.invite');
});

require __DIR__.'/auth.php';
