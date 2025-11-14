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

    Route::prefix('cdcs')->name('cdcs.')->group(function () {
        Route::get('/', [CdcController::class, 'index'])->name('index');
        Route::get('/create', [CdcController::class, 'create'])->name('create');
        Route::post('/', [CdcController::class, 'store'])->name('store');
        Route::get('/{cdc}', [CdcController::class, 'show'])->name('show');
        Route::put('/{cdc}', [CdcController::class, 'update'])->name('update');
        Route::get('/{cdc}/download', [CdcController::class, 'download'])->name('download');
        Route::delete('/{cdc}', [CdcController::class, 'destroy'])->name('destroy');
    });

    Route::resource('forms', FormController::class);

    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });

    Route::middleware(['role:admin|super-admin'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::resource('users', UserController::class);
            Route::post('users/{user}/roles', [UserController::class, 'updateRoles'])
                ->name('users.roles.update');
        });
});

require __DIR__.'/auth.php';
