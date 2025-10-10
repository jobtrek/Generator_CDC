<?php

use App\Http\Controllers\CdcController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::resource('forms', FormController::class);

    Route::get('/cdcs', [CdcController::class, 'index'])->name('cdcs.index');
    Route::post('/cdcs/generate/{form}', [CdcController::class, 'generate'])->name('cdcs.generate');
    Route::get('/cdcs/{cdc}', [CdcController::class, 'show'])->name('cdcs.show');
    Route::get('/cdcs/{cdc}/download', [CdcController::class, 'download'])->name('cdcs.download');
    Route::delete('/cdcs/{cdc}', [CdcController::class, 'destroy'])->name('cdcs.destroy');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware(['role:admin|super-admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', function () {
            $stats = [
                'total_users' => \App\Models\User::count(),
                'total_cdc' => \App\Models\Cdc::count(), // Corrigé aussi
                'total_forms' => \App\Models\Form::count(),
                'total_templates' => 0,
            ];
            return view('admin.dashboard', compact('stats'));
        })->name('dashboard');

        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', function () {
                $users = \App\Models\User::with('roles')->paginate(10);
                return view('admin.users.index', compact('users'));
            })->name('index');

            Route::get('/create', function () {
                $roles = \Spatie\Permission\Models\Role::all();
                return view('admin.users.create', compact('roles'));
            })->name('create');

            Route::post('/', function () {
                $validated = request()->validate([
                    'name' => 'required|string|max:255',
                    'email' => 'required|email|unique:users',
                    'password' => 'required|min:8|confirmed',
                    'roles' => 'array'
                ]);

                $user = \App\Models\User::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'password' => bcrypt($validated['password']),
                ]);

                if (!empty($validated['roles'])) {
                    $user->assignRole($validated['roles']);
                }

                return redirect()->route('admin.users.index')
                    ->with('success', 'Utilisateur créé avec succès');
            })->name('store');

            Route::get('/{id}/edit', function ($id) {
                $user = \App\Models\User::findOrFail($id);
                $roles = \Spatie\Permission\Models\Role::all();
                return view('admin.users.edit', compact('user', 'roles'));
            })->name('edit');

            Route::put('/{id}', function ($id) {
                $user = \App\Models\User::findOrFail($id);

                $validated = request()->validate([
                    'name' => 'required|string|max:255',
                    'email' => 'required|email|unique:users,email,' . $id,
                    'password' => 'nullable|min:8|confirmed',
                ]);

                $user->update([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                ]);

                if (!empty($validated['password'])) {
                    $user->update(['password' => bcrypt($validated['password'])]);
                }

                return redirect()->route('admin.users.index')
                    ->with('success', 'Utilisateur mis à jour');
            })->name('update');

            Route::delete('/{id}', function ($id) {
                if (auth()->id() == $id) {
                    return back()->with('error', 'Vous ne pouvez pas vous supprimer vous-même');
                }

                \App\Models\User::findOrFail($id)->delete();

                return redirect()->route('admin.users.index')
                    ->with('success', 'Utilisateur supprimé');
            })->name('destroy');

            Route::post('/{id}/roles', function ($id) {
                $user = \App\Models\User::findOrFail($id);
                $roles = request()->input('roles', []);
                $user->syncRoles($roles);

                return redirect()->route('admin.users.index')
                    ->with('success', 'Rôles mis à jour avec succès');
            })->name('roles.update');
        });
    });
});

require __DIR__.'/auth.php';
