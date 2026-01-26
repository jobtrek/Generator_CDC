<?php

use App\Http\Controllers\CdcController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Groupe accessible à tous les utilisateurs connectés
Route::middleware(['auth'])->group(function () {

    // Le dashboard est accessible SANS 'verified' pour afficher le popup
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // ROUTES PROTÉGÉES : L'utilisateur doit être vérifié pour accéder à ces fonctionnalités
    Route::middleware(['verified'])->group(function () {

        // Routes CDC
        Route::prefix('cdcs')->name('cdcs.')->group(function () {
            Route::get('/create', [CdcController::class, 'create'])->name('create');
            Route::post('/', [CdcController::class, 'store'])->name('store');
            Route::get('/{cdc}/download', [CdcController::class, 'download'])->name('download');
        });

        // Routes formulaires
        Route::resource('forms', FormController::class);

        // Routes profil
        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/', [ProfileController::class, 'edit'])->name('edit');
            Route::patch('/', [ProfileController::class, 'update'])->name('update');
            Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
        });

        // Routes admin
        Route::middleware(['role:admin|super-admin'])
            ->prefix('admin')
            ->name('admin.')
            ->group(function () {
                Route::resource('users', UserController::class);
                Route::post('users/{user}/roles', [UserController::class, 'updateRoles'])
                    ->name('users.roles.update');
            });
    });
});

require __DIR__.'/auth.php';
