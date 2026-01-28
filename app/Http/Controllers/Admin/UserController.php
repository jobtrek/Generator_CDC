<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password; // Nécessaire pour le lien d'invitation
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UserController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $users = User::with('roles')->paginate(15);
        return view('admin.users.index', compact('users'));
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

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make(Str::random(32)),
            'email_verified_at' => now(),
        ]);

        if (!empty($validated['roles'])) {
            $user->assignRole($validated['roles']);
        }


        $token = Password::createToken($user);

        $user->sendPasswordResetNotification($token);

        return redirect()->route('admin.users.index')
            ->with('success', "Invitation envoyée à {$user->email}. L'utilisateur a reçu un lien pour définir son mot de passe.");
    }

    public function show(User $user)
    {
        $user->load('roles', 'forms', 'cdcs');
        return view('admin.users.show', compact('user'));
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

    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            return back()->with('error', 'Vous ne pouvez pas vous supprimer vous-même.');
        }

        $userName = $user->name;
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "L'utilisateur \"{$userName}\" a été supprimé avec succès.");
    }

    public function updateRoles(Request $request, User $user)
    {
        $validated = $request->validate([
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,name'
        ]);

        $user->syncRoles($validated['roles']);

        return redirect()->route('admin.users.index')
            ->with('success', 'Rôles mis à jour avec succès.');
    }

    public function verifyEmail(User $user)
    {
        if ($user->hasVerifiedEmail()) {
            return back()->with('error', 'Cet utilisateur a déjà validé son email.');
        }

        $user->markEmailAsVerified();

        return back()->with('success', "L'email de l'utilisateur {$user->name} a été validé manuellement.");
    }
}
