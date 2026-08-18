<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\CdcController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\ProfileController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {
        $user = Auth::user();
        $formsCount = $user->forms()->count();
        $recentForms = $user->forms()->with('cdc')->latest()->take(4)->get();
        $usersCount = $user->hasRole('super-admin') ? User::count() : null;

        return view('dashboard', compact('formsCount', 'recentForms', 'usersCount'));
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
            Route::get('users/{user}/roles', [UserController::class, 'showRole'])
                ->name('users.roles.show');
            Route::post('users/{user}/roles', [UserController::class, 'updateRole'])
                ->name('users.roles');
        });
});

require __DIR__.'/auth.php';
