<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Modifier : {{ $user->name }}
            </h2>
            <a href="{{ route('admin.users.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition">
                Retour
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if ($errors->any())
                        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r">
                            <ul class="list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.users.update', $user->id) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Nom complet</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Adresse email</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                        </div>

                        <div class="border-t pt-4">
                            <h3 class="text-md font-medium text-gray-900 mb-4">Sécurité</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700">Nouveau mot de passe</label>
                                    <input type="password" name="password" id="password" placeholder="Laisser vide pour ne pas changer" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </div>
                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmer le mot de passe</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </div>
                            </div>
                        </div>

                        <div class="border-t pt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Rôle</label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @foreach($roles as $role)
                                    @if($role->name === 'super-admin' || $role->name === 'user')
                                        <div class="relative flex items-start py-3 px-4 border rounded-lg hover:bg-gray-50 cursor-pointer transition {{ $user->hasRole($role->name) ? 'border-indigo-500 ring-1 ring-indigo-500 bg-indigo-50' : 'border-gray-200' }}">
                                            <div class="min-w-0 flex-1 text-sm">
                                                <label for="role_{{ $role->id }}" class="font-medium text-gray-700 cursor-pointer">
                                                    {{ $role->name === 'super-admin' ? 'Super Administrateur' : 'Utilisateur' }}
                                                </label>
                                            </div>
                                            <div class="ml-3 flex items-center h-5">
                                                <input id="role_{{ $role->id }}" name="role" type="radio" value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'checked' : '' }} class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        <div class="flex items-center justify-between pt-6 border-t border-gray-100">
                            @if(auth()->id() !== $user->id)
                                <button type="button" onclick="if(confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')) { document.getElementById('delete-form').submit(); }" class="text-red-600 hover:text-red-900 text-sm font-bold">
                                    Supprimer cet utilisateur
                                </button>
                            @else
                                <div></div>
                            @endif

                            <div class="flex items-center space-x-3">
                                <a href="{{ route('admin.users.index') }}" class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 font-medium py-2 px-4 rounded-md shadow-sm transition">
                                    Annuler
                                </a>
                                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-md shadow-sm transition">
                                    Enregistrer
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
