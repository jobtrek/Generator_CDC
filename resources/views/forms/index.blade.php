<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight">Mes cahiers des charges</h2>
                <p class="mt-1 text-sm text-gray-500">Gérez et organisez vos documents administratifs.</p>
            </div>
            <a href="{{ route('forms.create') }}"
               class="inline-flex items-center px-5 py-2.5 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 focus:ring-4 focus:ring-gray-300 transition shadow-lg shadow-gray-900/20">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nouveau CDC
            </a>
        </div>
    </x-slot>

    <div class="py-10 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-5">

            {{-- Alertes --}}
            @if(session('success') || session('error'))
                <div x-data="{ show: true }" x-show="show" x-transition.duration.300ms
                     class="rounded-xl p-4 shadow-sm border flex justify-between items-center
                            {{ session('error') ? 'bg-red-50 border-red-100 text-red-700' : 'bg-emerald-50 border-emerald-100 text-emerald-700' }}">
                    <div class="flex items-center gap-2.5">
                        @if(session('success'))
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        @else
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        @endif
                        <span class="font-medium text-sm">{{ session('success') ?? session('error') }}</span>
                    </div>
                    <button @click="show = false" class="opacity-50 hover:opacity-100 transition ml-4">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                    </button>
                </div>
            @endif

            {{-- Barre de recherche --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <form method="GET" action="{{ route('forms.index') }}" class="flex gap-2">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Rechercher par nom..."
                               class="block w-full pl-9 pr-4 py-2.5 bg-gray-50 border border-gray-200 text-gray-900 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 text-sm transition placeholder-gray-400 hover:bg-white">
                    </div>
                    <button type="submit" class="px-4 py-2.5 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition shadow-sm">
                        Rechercher
                    </button>
                    @if(request('search'))
                        <a href="{{ route('forms.index') }}" class="px-4 py-2.5 bg-white border border-gray-200 text-gray-600 text-sm font-medium rounded-lg hover:bg-gray-50 transition flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            Réinitialiser
                        </a>
                    @endif
                </form>
            </div>

            {{-- Liste --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

                @if($forms->count() > 0)
                    {{-- En-tête --}}
                    <div class="px-6 py-3 border-b border-gray-100 bg-gray-50/70 grid grid-cols-12 gap-4 items-center">
                        <div class="col-span-8 text-xs font-semibold text-gray-400 uppercase tracking-wider">Document</div>
                        <div class="col-span-4 text-xs font-semibold text-gray-400 uppercase tracking-wider text-right">Actions</div>
                    </div>

                    @foreach($forms as $form)
                        @php $cdc = $form->cdc; $isDraft = $cdc && $cdc->status === 'brouillon'; @endphp
                        <div class="group grid grid-cols-12 gap-4 items-center px-6 py-4 border-b border-gray-100 last:border-0 transition-colors duration-150
                                    {{ $isDraft ? 'bg-amber-50/40 hover:bg-amber-50/70 border-l-2 border-l-amber-400' : 'hover:bg-gray-50/80' }}">

                            {{-- Nom --}}
                            <div class="col-span-8 flex items-center gap-3 min-w-0">
                                <div class="flex-shrink-0 w-9 h-9 rounded-lg flex items-center justify-center
                                            {{ $isDraft ? 'bg-amber-100 text-amber-600' : 'bg-indigo-50 text-indigo-500' }}">
                                    @if($isDraft)
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2">
                                        <p class="text-sm font-semibold text-gray-900 truncate group-hover:text-indigo-600 transition-colors">
                                            {{ $form->name }}
                                        </p>
                                        @if($isDraft)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-700 border border-amber-200 shrink-0">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                                Brouillon
                                            </span>
                                        @endif
                                    </div>
                                    @if($cdc && $cdc->data && isset($cdc->data['candidat_nom']))
                                        <p class="text-xs text-gray-400 truncate mt-0.5">
                                            {{ $cdc->data['candidat_nom'] }} {{ $cdc->data['candidat_prenom'] ?? '' }}
                                        </p>
                                    @endif
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="col-span-4 flex justify-end items-center gap-1.5">
                                @if($isDraft)
                                    <a href="{{ route('forms.edit', $form) }}"
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold rounded-lg transition shadow-sm shadow-amber-500/30">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Continuer
                                    </a>
                                @else
                                    @if($cdc)
                                        <a href="{{ route('cdc.download', $cdc) }}"
                                           class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-400 hover:text-blue-600 hover:border-blue-200 hover:bg-blue-50 transition"
                                           title="Télécharger Word">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        </a>
                                    @endif
                                    <a href="{{ route('forms.show', $form) }}"
                                       class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-400 hover:text-indigo-600 hover:border-indigo-200 hover:bg-indigo-50 transition"
                                       title="Voir">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    <a href="{{ route('forms.edit', $form) }}"
                                       class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-400 hover:text-amber-600 hover:border-amber-200 hover:bg-amber-50 transition"
                                       title="Modifier">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                @endif
                                <form method="POST" action="{{ route('forms.destroy', $form) }}"
                                      onsubmit="return confirm('Supprimer « {{ addslashes($form->name) }} » ?');" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-400 hover:text-red-600 hover:border-red-200 hover:bg-red-50 transition"
                                            title="Supprimer">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach

                    @if($forms->hasPages())
                        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">{{ $forms->links() }}</div>
                    @endif

                @else
                    <div class="text-center py-20 px-6">
                        <div class="mx-auto w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mb-5">
                            <svg class="w-8 h-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        @if(request('search'))
                            <h3 class="text-base font-semibold text-gray-800">Aucun résultat pour « {{ request('search') }} »</h3>
                            <p class="mt-1 text-sm text-gray-400">Essayez avec d'autres mots-clés.</p>
                            <div class="mt-5">
                                <a href="{{ route('forms.index') }}" class="text-sm text-indigo-600 hover:underline">Réinitialiser la recherche</a>
                            </div>
                        @else
                            <h3 class="text-base font-semibold text-gray-800">Aucun cahier des charges</h3>
                            <p class="mt-1 text-sm text-gray-400">Commencez par créer votre premier CDC.</p>
                            <div class="mt-6">
                                <a href="{{ route('forms.create') }}"
                                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white font-medium text-sm rounded-lg hover:bg-indigo-700 transition shadow-lg shadow-indigo-500/25">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    Créer un CDC
                                </a>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            @if($forms->count() > 0)
                <p class="text-center text-xs text-gray-400">
                    {{ $forms->total() }} document{{ $forms->total() > 1 ? 's' : '' }} au total
                </p>
            @endif

        </div>
    </div>
</x-app-layout>
