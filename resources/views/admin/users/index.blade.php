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
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rôle(s)</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date création</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
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
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex space-x-3">
                                        @can('user.edit')
                                            <a href="{{ route('admin.users.edit', $user->id) }}"
                                               class="text-indigo-600 hover:text-indigo-900"
                                               title="Modifier">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          d="M15.232 5.232l3.536 3.536m-2.036-1.5a2.5 2.5 0 113.536 3.536L7.5 21H3v-4.5L16.732 7.268z"/>
                                                </svg>
                                            </a>
                                        @endcan

                                        @can('user.roles')
                                            <button onclick="openRoleModal({{ $user->id }}, '{{ $user->name }}', {{ json_encode($user->getRoleNames()) }})"
                                                    class="text-blue-600 hover:text-blue-900"
                                                    title="Attribuer des rôles">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          d="M12 4v1m0 14v1m8-8h1M4 12H3m15.364 6.364l.707.707M6.343 6.343l-.707-.707m12.728 0l.707-.707M6.343 17.657l-.707.707M12 8a4 4 0 100 8 4 4 0 000-8z"/>
                                                </svg>
                                            </button>
                                        @endcan

                                        @can('user.delete')
                                            @if(auth()->id() !== $user->id)
                                                <form class="inline" method="POST"
                                                      action="{{ route('admin.users.destroy', $user->id) }}"
                                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="text-red-600 hover:text-red-900"
                                                            title="Supprimer">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                  d="M6 18L18 6M6 6l12 12"/>
                                                        </svg>
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
