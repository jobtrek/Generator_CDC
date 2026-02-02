<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                    Mes formulaires
                </h2>
                <p class="mt-1 text-sm text-gray-500">Gérez et organisez vos documents administratifs.</p>
            </div>


            <a href="{{ route('forms.create') }}"
               class="inline-flex items-center px-5 py-2.5 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 focus:ring-4 focus:ring-gray-300 transition shadow-lg shadow-gray-900/20">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Nouveau cahier des charges
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Section Alertes --}}
            @if(session('success') || session('error'))
                <div x-data="{ show: true }" x-show="show" x-transition.duration.300ms
                     class="rounded-lg p-4 shadow-sm border {{ session('error') ? 'bg-red-50 border-red-100 text-red-700' : 'bg-emerald-50 border-emerald-100 text-emerald-700' }} flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <span class="font-medium">{{ session('success') ?? session('error') }}</span>
                    </div>
                    <button @click="show = false" class="opacity-60 hover:opacity-100 transition">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                    </button>
                </div>
            @endif

            {{-- Barre de Recherche Simplifiée --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <form method="GET" action="{{ route('forms.index') }}" class="flex gap-2">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Rechercher un formulaire..."
                               class="block w-full pl-10 pr-4 py-2.5 bg-gray-50 border-gray-200 text-gray-900 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 text-sm transition placeholder-gray-400 hover:bg-white hover:border-gray-300">
                    </div>

                    <button type="submit" class="px-5 py-2.5 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition shadow-sm">
                        Rechercher
                    </button>

                    @if(request('search'))
                        <a href="{{ route('forms.index') }}" class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition flex items-center">
                            Réinitialiser
                        </a>
                    @endif
                </form>
            </div>

            {{-- Liste des Formulaires --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                @if($forms->count() > 0)
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                             {{ $forms->total() }} Résultat{{ $forms->total() > 1 ? 's' : '' }}
                        </span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-100">
                            <tbody class="divide-y divide-gray-100">
                            @foreach($forms as $form)
                                <tr class="group hover:bg-gray-50 transition duration-150 ease-in-out">
                                    <td class="px-6 py-5 whitespace-nowrap">
                                    <td class="px-1 py-5 whitespace-nowrap">
                                        <div class="flex items-center justify-start">
                                            <div class="flex-shrink-0 h-10 w-10 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center mr-3">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="text-sm font-semibold text-gray-900 group-hover:text-indigo-600 transition-colors">
                                                    {{ $form->name }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end items-center gap-2">
                                            @php $cdc = $form->cdcs()->first(); @endphp
                                            @if($cdc)
                                                <a href="{{ route('cdcs.download', $cdc) }}" class="group/btn flex items-center justify-center w-9 h-9 bg-white border border-gray-200 rounded-full text-gray-400 transition-all duration-200 hover:border-blue-200 hover:bg-blue-50 hover:text-blue-600 hover:shadow-sm" title="Télécharger Word">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                </a>
                                            @endif
                                            <a href="{{ route('forms.show', $form) }}" class="group/btn flex items-center justify-center w-9 h-9 bg-white border border-gray-200 rounded-full text-gray-400 transition-all duration-200 hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-600 hover:shadow-sm" title="Voir">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            </a>
                                            <a href="{{ route('forms.edit', $form) }}" class="group/btn flex items-center justify-center w-9 h-9 bg-white border border-gray-200 rounded-full text-gray-400 transition-all duration-200 hover:border-amber-200 hover:bg-amber-50 hover:text-amber-600 hover:shadow-sm" title="Modifier">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </a>
                                            <form method="POST" action="{{ route('forms.destroy', $form) }}" onsubmit="return confirm('Supprimer ce formulaire ?');" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="group/btn flex items-center justify-center w-9 h-9 bg-white border border-gray-200 rounded-full text-gray-400 transition-all duration-200 hover:border-red-200 hover:bg-red-50 hover:text-red-600 hover:shadow-sm" title="Supprimer">
                                                    <svg class="w-4 h-4 transition-transform group-hover/btn:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($forms->hasPages())
                        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">{{ $forms->links() }}</div>
                    @endif
                @else
                    <div class="text-center py-20 px-6">
                        <div class="mx-auto h-24 w-24 bg-gray-50 rounded-full flex items-center justify-center mb-6">
                            <svg class="h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        </div>
                        @if(request('search'))
                            <h3 class="text-lg font-medium text-gray-900">Aucun résultat</h3>
                            <div class="mt-6"><a href="{{ route('forms.index') }}" class="text-indigo-600 hover:underline">Réinitialiser la recherche</a></div>
                        @else
                            <h3 class="text-lg font-medium text-gray-900">Aucun formulaire</h3>
                            <div class="mt-8"><a href="{{ route('forms.create') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-medium text-sm rounded-lg hover:bg-indigo-700 shadow-lg shadow-indigo-500/30">Créer un formulaire</a></div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
