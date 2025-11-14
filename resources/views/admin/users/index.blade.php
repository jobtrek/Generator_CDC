<?php // resources/views/admin/users/index.blade.php ?>
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Gestion des Utilisateurs
            </h2>
            @can('user.create')
                <a href="{{ route('admin.users.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Nouvel Utilisateur
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-8 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-green-700 hover:text-green-900">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div x-data="{ show: true }" x-show="show" x-transition class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button @click="show = false" class="text-red-700 hover:text-red-900">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Table for large screens --}}
                    <div class="hidden lg:block">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Utilisateur
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Email
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Rôles
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Créé le
                                    </th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($users as $user)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 bg-indigo-100 rounded-full flex items-center justify-center">
                                                    <span class="text-indigo-600 font-semibold text-sm">
                                                        {{ substr($user->name, 0, 2) }}
                                                    </span>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $user->name }}
                                                    </div>
                                                    @if($user->id === auth()->id())
                                                        <span class="text-xs text-indigo-600">(Vous)</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900">{{ $user->email }}</div>
                                            @if($user->email_verified_at)
                                                <span class="inline-flex items-center text-xs text-green-600">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                    </svg>
                                                    Vérifié
                                                </span>
                                            @else
                                                <span class="inline-flex items-center text-xs text-red-600">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                                    </svg>
                                                    Non vérifié
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex flex-wrap gap-1">
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
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $user->created_at->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end gap-2">
                                                @can('user.edit')
                                                    <a href="{{ route('admin.users.edit', $user->id) }}"
                                                       class="inline-flex items-center p-2 text-blue-600 hover:text-blue-900 hover:bg-blue-50 rounded transition"
                                                       title="Modifier">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                        </svg>
                                                    </a>
                                                @endcan

                                                @can('user.roles')
                                                    <button onclick="openRoleModal({{ $user->id }}, '{{ addslashes($user->name) }}', {{ json_encode($user->getRoleNames()) }})"
                                                            class="inline-flex items-center p-2 text-purple-600 hover:text-purple-900 hover:bg-purple-50 rounded transition"
                                                            title="Gérer les rôles">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
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
                                                                    class="inline-flex items-center p-2 text-red-600 hover:text-red-900 hover:bg-red-50 rounded transition"
                                                                    title="Supprimer">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    @endif
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Card grid for tablet and mobile --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:hidden">
                        @foreach($users as $user)
                            <div class="bg-gray-50 rounded-lg p-4 shadow-sm hover:shadow-md transition">
                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0 h-12 w-12 bg-indigo-100 rounded-full flex items-center justify-center">
                                        <span class="text-indigo-600 font-semibold">{{ substr($user->name, 0, 2) }}</span>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                                <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                            </div>
                                            <div class="text-right">
                                                <div class="text-xs text-gray-500">{{ $user->created_at->format('d/m/Y') }}</div>
                                            </div>
                                        </div>

                                        <div class="mt-3 flex flex-wrap gap-2 items-center">
                                            <div class="flex flex-wrap gap-1">
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
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $color }}">
                                                        {{ ucfirst($role) }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>

                                        <div class="mt-3 flex gap-2">
                                            @can('user.edit')
                                                <a href="{{ route('admin.users.edit', $user->id) }}"
                                                   class="inline-flex items-center px-3 py-1 bg-white border border-gray-200 text-sm text-blue-600 rounded hover:bg-blue-50 transition">
                                                    Modifier
                                                </a>
                                            @endcan

                                            @can('user.roles')
                                                <button onclick="openRoleModal({{ $user->id }}, '{{ addslashes($user->name) }}', {{ json_encode($user->getRoleNames()) }})"
                                                        class="inline-flex items-center px-3 py-1 bg-white border border-gray-200 text-sm text-purple-600 rounded hover:bg-purple-50 transition">
                                                    Rôles
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
                                                                class="inline-flex items-center px-3 py-1 bg-white border border-gray-200 text-sm text-red-600 rounded hover:bg-red-50 transition">
                                                            Supprimer
                                                        </button>
                                                    </form>
                                                @endif
                                            @endcan
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Responsive modal --}}
    <div id="roleModal" class="hidden fixed inset-0 z-50 flex items-start sm:items-center justify-center px-4">
        <div class="absolute inset-0 bg-gray-600 bg-opacity-50" onclick="closeRoleModal()"></div>
        <div class="relative mt-16 sm:mt-0 w-full max-w-md bg-white rounded-lg shadow-lg overflow-hidden z-10">
            <div class="p-4 border-b flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-900 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    Modifier les rôles
                </h3>
                <button onclick="closeRoleModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="p-4">
                <div class="mb-4 p-3 bg-blue-50 rounded-lg border border-blue-200">
                    <p class="text-sm text-gray-700">
                        <span class="font-medium">Utilisateur :</span>
                        <span id="userName" class="text-gray-900"></span>
                    </p>
                </div>

                <form id="roleForm" method="POST">
                    @csrf
                    <div class="space-y-2 max-h-64 overflow-auto pr-2">
                        @foreach(\Spatie\Permission\Models\Role::all() as $role)
                            <label class="flex items-center p-2 bg-gray-50 rounded-lg hover:bg-gray-100 cursor-pointer transition">
                                <input type="checkbox"
                                       name="roles[]"
                                       value="{{ $role->name }}"
                                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <span class="ml-3 text-sm font-medium text-gray-900">{{ ucfirst($role->name) }}</span>
                            </label>
                        @endforeach
                    </div>

                    <div class="mt-4 flex justify-end gap-3">
                        <button type="button"
                                onclick="closeRoleModal()"
                                class="inline-flex items-center px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition">
                            Annuler
                        </button>
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                            Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openRoleModal(userId, userName, currentRoles) {
            document.getElementById('userName').textContent = userName;
            document.getElementById('roleForm').action = `/admin/users/${userId}/roles`;
            document.getElementById('roleModal').classList.remove('hidden');

            document.querySelectorAll('#roleForm input[type="checkbox"]').forEach(checkbox => {
                checkbox.checked = currentRoles.includes(checkbox.value);
            });
            // Prevent background scroll on mobile/tablet when modal is open
            document.documentElement.style.overflow = 'hidden';
        }

        function closeRoleModal() {
            document.getElementById('roleModal').classList.add('hidden');
            document.documentElement.style.overflow = '';
        }
    </script>
</x-app-layout>
