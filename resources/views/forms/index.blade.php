<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Mes formulaires
            </h2>
            <a href="{{ route('forms.create') }}"
               class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Nouveau formulaire
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition
                     class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded flex items-center justify-between">
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
                <div x-data="{ show: true }" x-show="show" x-transition
                     class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded flex items-center justify-between">
                    <span>{{ session('error') }}</span>
                    <button @click="show = false" class="text-red-700 hover:text-red-900">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6" x-data="{ showFilters: false }">
                <div class="p-6">
                    <form method="GET" action="{{ route('forms.index') }}">
                        <div class="flex flex-col md:flex-row gap-4 mb-4">
                            <div class="flex-1">
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                        </svg>
                                    </div>
                                    <input type="text"
                                           name="search"
                                           value="{{ request('search') }}"
                                           placeholder="Rechercher par nom ou description..."
                                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                </div>
                            </div>

                            <div class="flex gap-2">
                                <button type="button"
                                        @click="showFilters = !showFilters"
                                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                                    </svg>
                                    Filtres
                                    @if(request()->hasAny(['status', 'date_from', 'date_to']))
                                        <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                            {{ collect(['status', 'date_from', 'date_to'])->filter(fn($key) => request()->filled($key))->count() }}
                                        </span>
                                    @endif
                                </button>

                                <button type="submit"
                                        class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                    Rechercher
                                </button>

                                @if(request()->hasAny(['search', 'status', 'date_from', 'date_to']))
                                    <a href="{{ route('forms.index') }}"
                                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                        Réinitialiser
                                    </a>
                                @endif
                            </div>
                        </div>

                        <div x-show="showFilters" x-transition
                             class="grid grid-cols-1 md:grid-cols-3 gap-4 p-4 bg-gray-50 rounded-lg border">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                                <select name="status"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Tous</option>
                                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Actif</option>
                                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactif</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Créé après le</label>
                                <input type="date"
                                       name="date_from"
                                       value="{{ request('date_from') }}"
                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Créé avant le</label>
                                <input type="date"
                                       name="date_to"
                                       value="{{ request('date_to') }}"
                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($forms->count() > 0)
                        <div class="mb-4 flex justify-between items-center">
                            <div class="text-sm text-gray-600">
                                {{ $forms->total() }} formulaire{{ $forms->total() > 1 ? 's' : '' }}
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Champs</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Créé le</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($forms as $form)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $form->name }}</div>
                                            @if($form->description)
                                                <div class="text-sm text-gray-500">{{ Str::limit($form->description, 50) }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $form->fields->count() }} champ{{ $form->fields->count() > 1 ? 's' : '' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($form->is_active)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                    </svg>
                                                    Actif
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    Inactif
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            {{ $form->created_at->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex justify-end gap-2">
                                                {{-- ✅ Bouton Générer CDC --}}
                                                <a href="{{ route('cdcs.create', ['form_id' => $form->id]) }}"
                                                   class="inline-flex items-center p-2 text-green-600 hover:text-green-900 hover:bg-green-50 rounded transition"
                                                   title="Générer un CDC">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                    </svg>
                                                </a>

                                                <a href="{{ route('forms.show', $form) }}"
                                                   class="inline-flex items-center p-2 text-indigo-600 hover:text-indigo-900 hover:bg-indigo-50 rounded transition"
                                                   title="Voir">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                </a>

                                                <a href="{{ route('forms.edit', $form) }}"
                                                   class="inline-flex items-center p-2 text-blue-600 hover:text-blue-900 hover:bg-blue-50 rounded transition"
                                                   title="Modifier">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                </a>

                                                <form method="POST"
                                                      action="{{ route('forms.destroy', $form) }}"
                                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce formulaire ?');"
                                                      class="inline">
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
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $forms->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            @if(request()->hasAny(['search', 'status', 'date_from', 'date_to']))
                                <h3 class="mt-4 text-lg font-medium text-gray-900">Aucun résultat trouvé</h3>
                                <p class="mt-2 text-sm text-gray-500">Essayez de modifier vos critères de recherche.</p>
                                <div class="mt-6">
                                    <a href="{{ route('forms.index') }}"
                                       class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                                        Réinitialiser les filtres
                                    </a>
                                </div>
                            @else
                                <h3 class="mt-4 text-lg font-medium text-gray-900">Aucun formulaire</h3>
                                <p class="mt-2 text-sm text-gray-500">Commencez par créer votre premier formulaire.</p>
                                <div class="mt-6">
                                    <a href="{{ route('forms.create') }}"
                                       class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        Créer mon premier formulaire
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
