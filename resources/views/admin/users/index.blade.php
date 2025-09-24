<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestion des Utilisateurs') }}
            </h2>
            @can('user.create')
                <a href="{{ route('admin.users.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Nouvel Utilisateur
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Messages de succès --}}
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- Tableau des utilisateurs --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nom
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Email
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Rôle(s)
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date création
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($users as $user)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $user->name }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $user->email }}
                                        </div>
                                        @if($user->email_verified_at)
                                            <span class="text-xs text-green-600">✓ Vérifié</span>
                                        @else
                                            <span class="text-xs text-red-600">✗ Non vérifié</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @foreach($user->getRoleNames() as $role)
                                            @php
                                                $colors = [
                                                    'super-admin' => 'bg-red-100 text-red-800',
                                                    'admin' => 'bg-orange-100 text-orange-800',
                                                    'manager' => 'bg-blue-100 text-blue-800',
                                                    'user' => 'bg-green-100 text-green-800',
                                                    'guest' => 'bg-gray-100 text-gray-800',
                                                ];
                                                $color = $colors[$role] ?? 'bg-gray-100 text-gray-800';
                                            @endphp
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $color }}">
                                                {{ ucfirst($role) }}
                                            </span>
                                        @endforeach
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $user->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        @can('user.edit')
                                            <a href="{{ route('admin.users.edit', $user->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                                Modifier
                                            </a>
                                        @endcan

                                        @can('user.roles')
                                            <button onclick="openRoleModal({{ $user->id }}, '{{ $user->name }}', {{ json_encode($user->getRoleNames()) }})"
                                                    class="text-blue-600 hover:text-blue-900 mr-3">
                                                Rôles
                                            </button>
                                        @endcan

                                        @can('user.delete')
                                            @if(auth()->id() !== $user->id)
                                                <form class="inline" method="POST" action="{{ route('admin.users.destroy', $user->id) }}"
                                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                                        Supprimer
                                                    </button>
                                                </form>
                                            @endif
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal pour assigner les rôles --}}
    <div id="roleModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Modifier les rôles</h3>
            <p class="text-sm text-gray-500 mb-4">Utilisateur : <span id="userName"></span></p>

            <form id="roleForm" method="POST">
                @csrf
                <div class="space-y-2">
                    @foreach(\Spatie\Permission\Models\Role::all() as $role)
                        <label class="flex items-center">
                            <input type="checkbox" name="roles[]" value="{{ $role->name }}" class="mr-2">
                            <span class="text-sm">{{ ucfirst($role->name) }}</span>
                        </label>
                    @endforeach
                </div>

                <div class="mt-4 flex justify-end space-x-2">
                    <button type="button" onclick="closeRoleModal()"
                            class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">
                        Annuler
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openRoleModal(userId, userName, currentRoles) {
            document.getElementById('userName').textContent = userName;
            document.getElementById('roleForm').action = `/admin/users/${userId}/roles`;
            document.getElementById('roleModal').classList.remove('hidden');

            // Cocher les rôles actuels
            document.querySelectorAll('#roleForm input[type="checkbox"]').forEach(checkbox => {
                checkbox.checked = currentRoles.includes(checkbox.value);
            });
        }

        function closeRoleModal() {
            document.getElementById('roleModal').classList.add('hidden');
        }
    </script>
</x-app-layout>

{{-- resources/views/admin/dashboard.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tableau de bord Administration') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Statistiques --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-2xl font-bold text-gray-900">{{ $stats['total_users'] ?? 0 }}</div>
                        <div class="text-sm text-gray-600">Utilisateurs total</div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-2xl font-bold text-blue-600">{{ $stats['total_cdc'] ?? 0 }}</div>
                        <div class="text-sm text-gray-600">CDC créés</div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-2xl font-bold text-green-600">{{ $stats['total_forms'] ?? 0 }}</div>
                        <div class="text-sm text-gray-600">Formulaires</div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-2xl font-bold text-purple-600">{{ $stats['total_templates'] ?? 0 }}</div>
                        <div class="text-sm text-gray-600">Templates</div>
                    </div>
                </div>
            </div>

            {{-- Menu d'administration --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @can('user.view')
                    <a href="{{ route('admin.users.index') }}" class="block">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-shadow">
                            <div class="p-6">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
                                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-lg font-medium text-gray-900">Utilisateurs</div>
                                        <div class="text-sm text-gray-500">Gérer les utilisateurs et leurs rôles</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                @endcan

                @if(auth()->user()->hasRole('super-admin'))
                    <a href="{{ route('admin.roles.index') }}" class="block">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-shadow">
                            <div class="p-6">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 bg-red-500 rounded-md p-3">
                                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-lg font-medium text-gray-900">Rôles & Permissions</div>
                                        <div class="text-sm text-gray-500">Configurer les rôles et permissions</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                @endif

                @can('logs.view')
                    <a href="{{ route('admin.logs') }}" class="block">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-shadow">
                            <div class="p-6">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-lg font-medium text-gray-900">Logs Système</div>
                                        <div class="text-sm text-gray-500">Consulter les logs d'activité</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                @endcan
            </div>
        </div>
    </div>
</x-app-layout>
