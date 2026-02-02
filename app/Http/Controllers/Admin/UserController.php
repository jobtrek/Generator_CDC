<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $users = User::with('roles')->paginate(10);
        $allRoles = Role::all();
        return view('admin.users.index', compact('users', 'allRoles'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,name'
        ]);

        $this->checkSuperAdminEscalation($validated['roles'] ?? []);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make(Str::random(32)),
            'email_verified_at' => now(),
        ]);

        if (!empty($validated['roles'])) {
            $user->assignRole($validated['roles']);
        } else {
            $user->assignRole('user');
        }

        $token = Password::createToken($user);

        return redirect()->route('admin.users.index')
            ->with('success', "Utilisateur créé avec succès.");
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|confirmed',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,name'
        ]);

        if (isset($validated['roles'])) {
            $this->checkSuperAdminEscalation($validated['roles']);
        }

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if (!empty($validated['password'])) {
            $user->update([
                'password' => Hash::make($validated['password'])
            ]);
        }

        if (isset($validated['roles'])) {
            $user->syncRoles($validated['roles']);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur mis à jour avec succès.');
    }

    public function updateRoles(Request $request, User $user)
    {
        $validated = $request->validate([
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,name'
        ]);

        $this->checkSuperAdminEscalation($validated['roles']);

        $user->syncRoles($validated['roles']);

        return redirect()->route('admin.users.index')
            ->with('success', 'Rôles mis à jour avec succès.');
    }

    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            return back()->with('error', 'Vous ne pouvez pas vous supprimer vous-même.');
        }

        if ($user->hasRole('super-admin') && !auth()->user()->hasRole('super-admin')) {
            return back()->with('error', 'Vous n\'avez pas les droits pour supprimer un Super Administrateur.');
        }

        $userName = $user->name;
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "L'utilisateur \"{$userName}\" a été supprimé avec succès.");
    }

    public function verifyEmail(User $user)
    {
        if ($user->hasVerifiedEmail()) {
            return back()->with('error', 'Cet utilisateur a déjà validé son email.');
        }
        $user->markEmailAsVerified();
        return back()->with('success', "Email validé manuellement.");
    }

    /**
     * Vérifie si l'utilisateur actuel a le droit d'assigner les rôles demandés.
     * Si on essaie d'assigner 'super-admin' sans être 'super-admin', on bloque.
     */
    private function checkSuperAdminEscalation(array $roles)
    {
        if (in_array('super-admin', $roles)) {
            if (!Auth::user()->hasRole('super-admin')) {
                abort(403, 'ACTION NON AUTORISÉE : Seul un Super Admin peut nommer un autre Super Admin.');
            }
        }
    }
}
