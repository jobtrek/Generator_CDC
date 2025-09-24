<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// ====================================
// ROUTES PUBLIQUES
// ====================================
Route::get('/', function () {
    return view('welcome');
});

// ====================================
// ROUTES AUTHENTIFIÉES
// ====================================
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard - Accessible à tous les utilisateurs connectés
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // ====================================
    // ROUTES CDC (Cahiers des Charges)
    // ====================================
    Route::prefix('cdc')->name('cdc.')->group(function () {

        // Voir la liste des CDC - Permission: cdc.view
        Route::get('/', function () {
            return view('cdc.index');
        })->name('index')->middleware('permission:cdc.view');

        // Créer un nouveau CDC - Permission: cdc.create
        Route::get('/create', function () {
            return view('cdc.create');
        })->name('create')->middleware('permission:cdc.create');

        // Sauvegarder un CDC - Permission: cdc.create
        Route::post('/', function () {
            // Logique de sauvegarde
            return redirect()->route('cdc.index')->with('success', 'CDC créé avec succès');
        })->name('store')->middleware('permission:cdc.create');

        // Voir un CDC spécifique - Permission: cdc.view
        Route::get('/{id}', function ($id) {
            return view('cdc.show', compact('id'));
        })->name('show')->middleware('permission:cdc.view');

        // Éditer un CDC - Permission: cdc.edit
        Route::get('/{id}/edit', function ($id) {
            return view('cdc.edit', compact('id'));
        })->name('edit')->middleware('permission:cdc.edit');

        // Mettre à jour un CDC - Permission: cdc.edit
        Route::put('/{id}', function ($id) {
            // Logique de mise à jour
            return redirect()->route('cdc.show', $id)->with('success', 'CDC mis à jour');
        })->name('update')->middleware('permission:cdc.edit');

        // Supprimer un CDC - Permission: cdc.delete
        Route::delete('/{id}', function ($id) {
            // Logique de suppression
            return redirect()->route('cdc.index')->with('success', 'CDC supprimé');
        })->name('destroy')->middleware('permission:cdc.delete');

        // Exporter un CDC en Word - Permission: cdc.export
        Route::get('/{id}/export', function ($id) {
            // Logique d'export
            return response()->download('cdc.docx');
        })->name('export')->middleware('permission:cdc.export');

        // Dupliquer un CDC - Permission: cdc.duplicate
        Route::post('/{id}/duplicate', function ($id) {
            // Logique de duplication
            return redirect()->route('cdc.index')->with('success', 'CDC dupliqué');
        })->name('duplicate')->middleware('permission:cdc.duplicate');
    });

    Route::prefix('forms')->name('forms.')->middleware('permission:form.view')->group(function () {
        Route::get('/', function () {
            return view('forms.index');
        })->name('index');

        Route::get('/create', function () {
            return view('forms.create');
        })->name('create')->middleware('permission:form.create');

        Route::post('/', function () {
            return redirect()->route('forms.index')->with('success', 'Formulaire créé');
        })->name('store')->middleware('permission:form.create');

        Route::get('/{id}/edit', function ($id) {
            return view('forms.edit', compact('id'));
        })->name('edit')->middleware('permission:form.edit');

        Route::delete('/{id}', function ($id) {
            return redirect()->route('forms.index')->with('success', 'Formulaire supprimé');
        })->name('destroy')->middleware('permission:form.delete');
    });

    Route::prefix('templates')->name('templates.')->middleware('permission:template.view')->group(function () {
        Route::get('/', function () {
            return view('templates.index');
        })->name('index');

        Route::get('/create', function () {
            return view('templates.create');
        })->name('create')->middleware('permission:template.create');

        Route::get('/{id}/edit', function ($id) {
            return view('templates.edit', compact('id'));
        })->name('edit')->middleware('permission:template.edit');

        Route::delete('/{id}', function ($id) {
            return redirect()->route('templates.index')->with('success', 'Template supprimé');
        })->name('destroy')->middleware('permission:template.delete');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware(['role:admin,super-admin'])->prefix('admin')->name('admin.')->group(function () {

        Route::get('/', function () {
            $stats = [
                'total_users' => \App\Models\User::count(),
                'total_cdc' => 0, // À implémenter quand vous aurez le modèle CDC
                'total_forms' => 0,
                'total_templates' => 0,
            ];
            return view('admin.dashboard', compact('stats'));
        })->name('dashboard');

        // Gestion des utilisateurs
        Route::prefix('users')->name('users.')->group(function () {

            // Liste des utilisateurs
            Route::get('/', function () {
                $users = \App\Models\User::with('roles')->paginate(10);
                return view('admin.users.index', compact('users'));
            })->name('index')->middleware('permission:user.view');

            // Créer un utilisateur
            Route::get('/create', function () {
                $roles = \Spatie\Permission\Models\Role::all();
                return view('admin.users.create', compact('roles'));
            })->name('create')->middleware('permission:user.create');

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
            })->name('store')->middleware('permission:user.create');

            // Éditer un utilisateur
            Route::get('/{id}/edit', function ($id) {
                $user = \App\Models\User::findOrFail($id);
                $roles = \Spatie\Permission\Models\Role::all();
                return view('admin.users.edit', compact('user', 'roles'));
            })->name('edit')->middleware('permission:user.edit');

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
            })->name('update')->middleware('permission:user.edit');

            // Supprimer un utilisateur
            Route::delete('/{id}', function ($id) {
                if (auth()->id() == $id) {
                    return back()->with('error', 'Vous ne pouvez pas vous supprimer vous-même');
                }

                \App\Models\User::findOrFail($id)->delete();

                return redirect()->route('admin.users.index')
                    ->with('success', 'Utilisateur supprimé');
            })->name('destroy')->middleware('permission:user.delete');

            // Gérer les rôles d'un utilisateur
            Route::post('/{id}/roles', function ($id) {
                $user = \App\Models\User::findOrFail($id);
                $roles = request()->input('roles', []);
                $user->syncRoles($roles);

                return redirect()->route('admin.users.index')
                    ->with('success', 'Rôles mis à jour avec succès');
            })->name('roles.update')->middleware('permission:user.roles');
        });

        // Gestion des rôles (Super Admin uniquement)
        Route::middleware(['role:super-admin'])->prefix('roles')->name('roles.')->group(function () {

            // Liste des rôles
            Route::get('/', function () {
                $roles = \Spatie\Permission\Models\Role::with('permissions')->get();
                return view('admin.roles.index', compact('roles'));
            })->name('index');

            // Créer un rôle
            Route::get('/create', function () {
                $permissions = \Spatie\Permission\Models\Permission::all();
                return view('admin.roles.create', compact('permissions'));
            })->name('create');

            Route::post('/', function () {
                $validated = request()->validate([
                    'name' => 'required|string|unique:roles',
                    'permissions' => 'array'
                ]);

                $role = \Spatie\Permission\Models\Role::create(['name' => $validated['name']]);

                if (!empty($validated['permissions'])) {
                    $role->givePermissionTo($validated['permissions']);
                }

                return redirect()->route('admin.roles.index')
                    ->with('success', 'Rôle créé avec succès');
            })->name('store');

            // Éditer un rôle
            Route::get('/{id}/edit', function ($id) {
                $role = \Spatie\Permission\Models\Role::findOrFail($id);
                $permissions = \Spatie\Permission\Models\Permission::all();
                return view('admin.roles.edit', compact('role', 'permissions'));
            })->name('edit');

            Route::put('/{id}', function ($id) {
                $role = \Spatie\Permission\Models\Role::findOrFail($id);

                $validated = request()->validate([
                    'name' => 'required|string|unique:roles,name,' . $id,
                    'permissions' => 'array'
                ]);

                $role->update(['name' => $validated['name']]);
                $role->syncPermissions($validated['permissions'] ?? []);

                return redirect()->route('admin.roles.index')
                    ->with('success', 'Rôle mis à jour');
            })->name('update');

            // Supprimer un rôle
            Route::delete('/{id}', function ($id) {
                $role = \Spatie\Permission\Models\Role::findOrFail($id);

                if (in_array($role->name, ['super-admin', 'admin', 'user'])) {
                    return back()->with('error', 'Ce rôle système ne peut pas être supprimé');
                }

                $role->delete();

                return redirect()->route('admin.roles.index')
                    ->with('success', 'Rôle supprimé');
            })->name('destroy');
        });

        // Logs système (Super Admin uniquement)
        Route::middleware(['role:super-admin'])->group(function () {
            Route::get('/logs', function () {
                return view('admin.logs');
            })->name('logs')->middleware('permission:logs.view');

            Route::get('/settings', function () {
                return view('admin.settings');
            })->name('settings')->middleware('permission:settings.view');
        });
    });
});

require __DIR__.'/auth.php';
