<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                    Gestion des Utilisateurs
                </h2>
                <p class="mt-1 text-sm text-gray-500">Administrez les comptes et les permissions.</p>
            </div>
            @can('user.create')
                <a href="{{ route('admin.users.create') }}"
                   class="inline-flex items-center px-5 py-2.5 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 focus:ring-4 focus:ring-gray-300 transition shadow-lg shadow-gray-900/20">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Nouvel Utilisateur
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-8 sm:py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Alertes --}}
            @if(session('success') || session('error'))
                <div x-data="{ show: true }" x-show="show" x-transition.duration.300ms
                     class="rounded-lg p-4 shadow-sm border {{ session('error') ? 'bg-red-50 border-red-100 text-red-700' : 'bg-emerald-50 border-emerald-100 text-emerald-700' }} flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        @if(session('error'))
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        @else
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        @endif
                        <span class="font-medium">{{ session('success') ?? session('error') }}</span>
                    </div>
                    <button @click="show = false" class="opacity-60 hover:opacity-100 transition">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                    </button>
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6">

                    <div class="hidden lg:block">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-100">
                                <thead class="bg-gray-50/50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Utilisateur</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Rôles</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                @foreach($users as $user)
                                    <tr class="hover:bg-gray-50 transition duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-9 w-9 bg-indigo-100 text-indigo-600 rounded-lg flex items-center justify-center text-sm font-bold">
                                                    {{ substr($user->name, 0, 2) }}
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-semibold text-gray-900">{{ $user->name }}</div>
                                                    @if($user->id === auth()->id())
                                                        <span class="text-xs text-indigo-600 font-medium bg-indigo-50 px-2 py-0.5 rounded-full">Vous</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-600 mb-1">{{ $user->email }}</div>

                                            @if($user->email_verified_at)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                                    Validé
                                                </span>
                                            @else
                                                                                    <div class="flex flex-col items-start gap-1">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    En attente
                                                </span>

                                                    <form action="{{ route('password.email') }}" method="POST" class="mt-1">
                                                        @csrf
                                                        <input type="hidden" name="email" value="{{ $user->email }}">
                                                        <button type="submit" class="text-xs text-indigo-600 hover:text-indigo-900 underline">
                                                            Renvoyer l'invitation
                                                        </button>
                                                    </form>
                                                </div>
                                            @endif
                                        </td>

                                        <td class="px-6 py-4">
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($user->getRoleNames() as $role)
                                                    @php
                                                        $colors = [
                                                            'super-admin' => 'bg-purple-50 text-purple-700 ring-purple-600/20',
                                                            'user' => 'bg-green-50 text-green-700 ring-green-600/20',
                                                        ];
                                                        $color = $colors[$role] ?? 'bg-gray-50 text-gray-600 ring-gray-500/10';
                                                    @endphp
                                                    <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $color }}">
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
                                                       class="group flex items-center justify-center w-8 h-8 bg-white border border-gray-200 rounded-full text-gray-400 transition-all duration-200 hover:border-blue-200 hover:bg-blue-50 hover:text-blue-600 hover:shadow-sm"
                                                       title="Modifier">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                                    </a>
                                                @endcan

                                                @can('user.roles')
                                                    <button onclick="openRoleModal({{ $user->id }}, '{{ addslashes($user->name) }}', {{ json_encode($user->getRoleNames()) }})"
                                                            class="group flex items-center justify-center w-8 h-8 bg-white border border-gray-200 rounded-full text-gray-400 transition-all duration-200 hover:border-purple-200 hover:bg-purple-50 hover:text-purple-600 hover:shadow-sm"
                                                            title="Gérer les rôles">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" /></svg>
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
                                                                    class="group flex items-center justify-center w-8 h-8 bg-white border border-gray-200 rounded-full text-gray-400 transition-all duration-200 hover:border-red-200 hover:bg-red-50 hover:text-red-600 hover:shadow-sm"
                                                                    title="Supprimer">
                                                                <svg class="w-4 h-4 transition-transform group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
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

                    {{-- Card Grid Mobile --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:hidden">
                        @foreach($users as $user)
                            <div class="bg-gray-50/50 rounded-xl p-4 border border-gray-100 shadow-sm hover:shadow-md transition">
                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0 h-10 w-10 bg-indigo-100 text-indigo-600 rounded-lg flex items-center justify-center font-bold text-sm">
                                        {{ substr($user->name, 0, 2) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <div class="text-sm font-semibold text-gray-900 truncate">{{ $user->name }}</div>
                                                <div class="text-xs text-gray-500 truncate">{{ $user->email }}</div>
                                            </div>
                                        </div>

                                        <div class="mt-2 flex flex-wrap gap-1">
                                            @foreach($user->getRoleNames() as $role)
                                                <span class="inline-flex items-center rounded-md px-1.5 py-0.5 text-[10px] font-medium bg-gray-100 text-gray-600 ring-1 ring-inset ring-gray-500/10">
                                                    {{ ucfirst($role) }}
                                                </span>
                                            @endforeach
                                        </div>

                                        <div class="mt-3 pt-3 border-t border-gray-200 flex justify-end gap-2">
                                            @can('user.edit')
                                                <a href="{{ route('admin.users.edit', $user->id) }}"
                                                   class="group flex items-center justify-center w-8 h-8 bg-white border border-gray-200 rounded-full text-gray-400 transition-all duration-200 hover:border-blue-200 hover:bg-blue-50 hover:text-blue-600">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                                </a>
                                            @endcan

                                            @can('user.roles')
                                                <button onclick="openRoleModal({{ $user->id }}, '{{ addslashes($user->name) }}', {{ json_encode($user->getRoleNames()) }})"
                                                        class="group flex items-center justify-center w-8 h-8 bg-white border border-gray-200 rounded-full text-gray-400 transition-all duration-200 hover:border-purple-200 hover:bg-purple-50 hover:text-purple-600">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" /></svg>
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
                                                                class="group flex items-center justify-center w-8 h-8 bg-white border border-gray-200 rounded-full text-gray-400 transition-all duration-200 hover:border-red-200 hover:bg-red-50 hover:text-red-600">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
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

    {{-- Modal Rôles --}}
    <div id="roleModal" class="hidden fixed inset-0 z-50 flex items-start sm:items-center justify-center px-4">
        <div class="absolute inset-0 bg-gray-600/80 backdrop-blur-sm transition-opacity" onclick="closeRoleModal()"></div>
        <div class="relative mt-16 sm:mt-0 w-full max-w-md bg-white rounded-xl shadow-2xl overflow-hidden z-10 transform transition-all scale-100">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <span class="p-1.5 bg-indigo-100 text-indigo-600 rounded-md">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" /></svg>
                    </span>
                    Modifier les rôles
                </h3>
                <button onclick="closeRoleModal()" class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="p-6">
                <div class="mb-6 p-4 bg-indigo-50/50 rounded-lg border border-indigo-100">
                    <p class="text-sm text-gray-700">
                        Gestion des permissions pour <span id="userName" class="font-bold text-gray-900"></span>
                    </p>
                </div>

                <form id="roleForm" method="POST">
                    @csrf
                    <div class="space-y-3 max-h-64 overflow-y-auto pr-2 custom-scrollbar">
                        @foreach(\Spatie\Permission\Models\Role::all() as $role)
                            <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:border-indigo-300 hover:bg-indigo-50 cursor-pointer transition-all group">
                                <input type="checkbox"
                                       name="roles[]"
                                       value="{{ $role->name }}"
                                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <span class="ml-3 text-sm font-medium text-gray-700 group-hover:text-indigo-700">{{ ucfirst($role->name) }}</span>
                            </label>
                        @endforeach
                    </div>

                    <div class="mt-6 flex justify-end gap-3 pt-4 border-t border-gray-100">
                        <button type="button"
                                onclick="closeRoleModal()"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                            Annuler
                        </button>
                        <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-lg shadow-indigo-500/30 transition">
                            Enregistrer les modifications
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
            const modal = document.getElementById('roleModal');
            modal.classList.remove('hidden');

            setTimeout(() => {
                modal.firstElementChild.classList.remove('opacity-0');
                modal.lastElementChild.classList.remove('scale-95', 'opacity-0');
            }, 10);

            document.querySelectorAll('#roleForm input[type="checkbox"]').forEach(checkbox => {
                checkbox.checked = currentRoles.includes(checkbox.value);
            });
            document.documentElement.style.overflow = 'hidden';
        }

        function closeRoleModal() {
            const modal = document.getElementById('roleModal');
            document.documentElement.style.overflow = '';
            modal.classList.add('hidden');
        }
    </script>
</x-app-layout>
