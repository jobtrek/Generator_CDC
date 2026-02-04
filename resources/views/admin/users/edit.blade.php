<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Modifier l\'utilisateur') }} : {{ $user->name }}
            </h2>
            <a href="{{ route('admin.users.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Retour
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.users.update', $user->id) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">
                                Nom complet
                            </label>
                            <input type="text"
                                   name="name"
                                   id="name"
                                   value="{{ old('name', $user->name) }}"
                                   required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">
                                Adresse email
                            </label>
                            <input type="email"
                                   name="email"
                                   id="email"
                                   value="{{ old('email', $user->email) }}"
                                   required
                                   pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}"
                                   title="Veuillez entrer une adresse email valide"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Statut de l'email
                            </label>
                            <div class="flex items-center space-x-4">
                                @if($user->email_verified_at)
                                    <span class="text-green-600 flex items-center">
                                        <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        Email vérifié le {{ $user->email_verified_at->timezone('Europe/Zurich')->format('d/m/Y à H:i') }}
                                    </span>
                                @else
                                    <span class="text-red-600 flex items-center">
                                        <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                        </svg>
                                        Email non vérifié
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="border-t pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Changer le mot de passe (optionnel)</h3>

                            <div class="space-y-4">
                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700" >
                                        Nouveau mot de passe
                                    </label>
                                    <input type="password"
                                           name="password"
                                           id="password"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                           placeholder="Laisser vide pour conserver l'actuel">
                                    @error('password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                                        Confirmer le nouveau mot de passe
                                    </label>
                                    <input type="password"
                                           name="password_confirmation"
                                           id="password_confirmation"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Rôles
                            </label>
                            <div class="space-y-2 border rounded-md p-3 bg-gray-50">
                                @forelse($roles ?? [] as $role)
                                    @if($role->name !== 'guest')
                                        <label class="flex items-center">
                                            <input type="checkbox"
                                                   name="roles[]"
                                                   value="{{ $role->name }}"
                                                   {{ in_array($role->name, old('roles', $user->roles->pluck('name')->toArray())) ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <span class="ml-2 text-sm text-gray-700">
                                                {{ ucfirst($role->name) }}
                                                @if($role->name == 'super-admin')
                                                    <span class="text-xs text-red-600">(Accès total)</span>
                                                @elseif($role->name == 'admin')
                                                    <span class="text-xs text-orange-600">(Administration)</span>
                                                @elseif($role->name == 'formateur')
                                                    <span class="text-xs text-blue-600">(Formateur)</span>
                                                @elseif($role->name == 'user')
                                                    <span class="text-xs text-green-600">(Utilisateur standard)</span>
                                                @endif
                                        </span>
                                        </label>
                                    @endif
                                @empty
                                    <p class="text-sm text-gray-500">Aucun rôle disponible</p>
                                @endforelse
                            </div>
                        </div>

                        <div class="border-t pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informations</h3>
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Créé le</dt>
                                    <dd class="text-sm text-gray-900">{{ $user->created_at->timezone('Europe/Zurich')->format('d/m/Y à H:i') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Dernière modification</dt>
                                    <dd class="text-sm text-gray-900">{{ $user->updated_at->timezone('Europe/Zurich')->format('d/m/Y à H:i') }}</dd>
                                </div>
                            </dl>
                        </div>

                        <div class="flex items-center justify-between border-t pt-6">
                            <div>
                                @if(auth()->id() !== $user->id)
                                    <button type="button"
                                            onclick="if(confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')) { document.getElementById('delete-form').submit(); }"
                                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                        Supprimer l'utilisateur
                                    </button>
                                @endif
                            </div>

                            <div class="flex items-center space-x-3">
                                <a href="{{ route('admin.users.index') }}"
                                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                    Annuler
                                </a>
                                <button type="submit"
                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Enregistrer les modifications
                                </button>
                            </div>
                        </div>
                    </form>

                    @if(auth()->id() !== $user->id)
                        <form id="delete-form" action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
