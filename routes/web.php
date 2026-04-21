<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\CdcController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::middleware(['verified'])->group(function () {
        Route::prefix('cdc')->name('cdc.')->group(function () {
            Route::get('/create', [CdcController::class, 'create'])->name('create');
            Route::get('/{cdc}/download', [CdcController::class, 'download'])->name('download');
        });
        Route::resource('forms', FormController::class);
        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/', [ProfileController::class, 'edit'])->name('edit');
            Route::patch('/', [ProfileController::class, 'update'])->name('update');
            Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
        });
    });

    Route::middleware(['role:super-admin'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::resource('users', UserController::class);
            Route::post('users/{user}/verify', [UserController::class, 'verifyEmail'])
                ->name('users.verify');
        });
});

require __DIR__.'/auth.php';
