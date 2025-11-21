<?php

use App\Http\Controllers\CdcController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // ✅ Routes CDC simplifiées (download uniquement)
    Route::prefix('cdcs')->name('cdcs.')->group(function () {
        Route::get('/create', [CdcController::class, 'create'])->name('create');
        Route::post('/', [CdcController::class, 'store'])->name('store');
        Route::get('/{cdc}/download', [CdcController::class, 'download'])->name('download');
    });

    // ✅ Routes formulaires (complètes)
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

    Route::get('/cdcs/{cdc}/download-pdf', [CdcController::class, 'downloadPdf'])
        ->name('cdcs.downloadPdf')
        ->middleware('auth');
});
require __DIR__.'/auth.php';
