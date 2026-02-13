<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Helpers\RoleHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class UserController extends Controller implements HasMiddleware
{
    use AuthorizesRequests;

    /**
     * Définition des middlewares pour ce contrôleur (Laravel 11+)
     */
    public static function middleware(): array
    {
        return [
            new Middleware(function ($request, $next) {
                $user = Auth::user();
                if (!$user || !$user->hasRole('super-admin')) {
                    abort(403, 'Accès réservé aux super-administrateurs.');
                }
                return $next($request);
            }),
        ];
    }

    public function index()
    {
        $users = User::with('roles')->paginate(10);
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
            'role' => 'required|string|exists:roles,name',
        ]);

        $currentUser = Auth::user();
        $targetRole = $validated['role'];

        if (!RoleHelper::canAssignRole($currentUser, $targetRole)) {
            abort(403, 'Vous ne pouvez pas assigner ce rôle.');
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make(Str::random(32)),
            'email_verified_at' => now(),
        ]);

        $user->assignRole($targetRole);

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur créé avec succès.');
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
            'role' => 'required|string|exists:roles,name',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $currentUser = Auth::user();
        $targetRole = $validated['role'];

        if ($currentUser->id === $user->id) {
            $currentRole = RoleHelper::getPrimaryRole($currentUser);
            if ($currentRole !== $targetRole && !$currentUser->hasRole('super-admin')) {
                return back()->with('error', 'Vous ne pouvez pas modifier votre propre rôle.');
            }
        }

        if (!RoleHelper::canAssignRole($currentUser, $targetRole)) {
            abort(403, 'Vous ne pouvez pas assigner ce rôle.');
        }

        $targetUserRole = RoleHelper::getPrimaryRole($user);
        if ($targetUserRole) {
            $myWeight = RoleHelper::getRoleWeight(RoleHelper::getPrimaryRole($currentUser));
            $targetWeight = RoleHelper::getRoleWeight($targetUserRole);

            if ($targetWeight >= $myWeight && !$currentUser->hasRole('super-admin')) {
                abort(403, 'Vous ne pouvez pas modifier un utilisateur avec un rôle égal ou supérieur au vôtre.');
            }
        }

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);
        $user->syncRoles([$targetRole]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur mis à jour avec succès.');
    }

    public function destroy(User $user)
    {
        $currentUser = Auth::user();

        if ($currentUser->id === $user->id) {
            return back()->with('error', 'Vous ne pouvez pas vous supprimer vous-même.');
        }

        $myRole = RoleHelper::getPrimaryRole($currentUser);
        $targetRole = RoleHelper::getPrimaryRole($user);

        $myWeight = RoleHelper::getRoleWeight($myRole);
        $targetWeight = RoleHelper::getRoleWeight($targetRole);

        if ($targetWeight >= $myWeight && !$currentUser->hasRole('super-admin')) {
            return back()->with('error', 'Droits insuffisants pour supprimer cet utilisateur.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur supprimé avec succès.');
    }

    public function verifyEmail(User $user)
    {
        if ($user->hasVerifiedEmail()) {
            return back()->with('error', 'Cet utilisateur a déjà validé son email.');
        }
        $user->markEmailAsVerified();
        return back()->with('success', 'Email validé manuellement.');
    }
}
