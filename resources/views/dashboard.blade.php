<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                    Pilotage des projets
                </h2>
                <p class="mt-1 text-sm text-gray-500">Vue d'ensemble de vos cahiers des charges.</p>
            </div>
            <div class="text-sm text-gray-500 bg-white px-3 py-1 rounded-full shadow-sm border border-gray-100">
                {{ now()->locale('fr')->isoFormat('dddd D MMMM YYYY') }}
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- POPUP DE VÉRIFICATION (S'affiche si non vérifié) --}}
            @if (! Auth::user()->hasVerifiedEmail())
                <div x-data="{ open: true }" x-show="open" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        {{-- Overlay sombre --}}
                        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full p-6">
                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-amber-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <svg class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                    <h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title">Vérifiez votre adresse email</h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500">
                                            Pour accéder à vos projets et créer de nouveaux cahiers des charges, vous devez valider votre compte via le mail envoyé à <strong>{{ Auth::user()->email }}</strong>.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-6 flex flex-col gap-3">
                                <form method="POST" action="{{ route('verification.send') }}">
                                    @csrf
                                    <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none sm:text-sm">
                                        Renvoyer le mail de vérification
                                    </button>
                                </form>
                                <p class="text-xs text-center text-gray-400 italic">Vérifiez vos spams si vous ne trouvez pas le mail.</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- 1. Section de Bienvenue & Actions Rapides --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-4">
                    <div class="h-14 w-14 bg-indigo-50 text-indigo-600 rounded-full flex items-center justify-center text-xl font-bold">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Ravi de vous revoir, {{ Auth::user()->name }} !</h3>
                        <p class="text-sm text-gray-500 font-medium">Prêt à structurer votre prochain projet ?</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 w-full md:w-auto">
                    {{-- Bouton activé ou désactivé selon la vérification --}}
                    @if(Auth::user()->hasVerifiedEmail())
                        <a href="{{ route('forms.create') }}" class="flex-1 md:flex-none inline-flex items-center justify-center px-6 py-3 bg-indigo-600 text-white text-sm font-medium rounded-xl hover:bg-indigo-700 transition-all shadow-sm">
                            Nouveau Cahier des Charges
                        </a>
                    @else
                        <button disabled class="flex-1 md:flex-none opacity-50 cursor-not-allowed inline-flex items-center justify-center px-6 py-3 bg-gray-400 text-white text-sm font-medium rounded-xl">
                            Compte non vérifié
                        </button>
                    @endif
                </div>
            </div>
            {{-- 2. Indicateurs Clés (KPI) --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                {{-- Carte 1 : Projets en cours (Mes formulaires) --}}
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition group">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-blue-50 text-blue-600 rounded-xl group-hover:bg-blue-600 group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        </div>
                        <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">Total</span>
                    </div>
                    <div>
                        <div class="text-3xl font-bold text-gray-900">{{ Auth::user()->forms()->count() }}</div>
                        <div class="text-sm text-gray-500 mt-1">Dossiers créés</div>
                    </div>
                </div>

                {{-- Carte 2 : Conditionnelle (Utilisateurs ou Projets Actifs) --}}
                @if(Auth::user()->hasAnyRole(['admin', 'super-admin']))
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition group">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-purple-50 text-purple-600 rounded-xl group-hover:bg-purple-600 group-hover:text-white transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            </div>
                            <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">Administration</span>
                        </div>
                        <div>
                            <div class="text-3xl font-bold text-gray-900">{{ \App\Models\User::count() }}</div>
                            <div class="text-sm text-gray-500 mt-1">Utilisateurs enregistrés</div>
                        </div>
                    </div>
                @else
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition group">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">Statut</span>
                        </div>
                        <div>
                            <div class="text-3xl font-bold text-gray-900">{{ Auth::user()->forms()->where('is_active', true)->count() }}</div>
                            <div class="text-sm text-gray-500 mt-1">Projets actifs</div>
                        </div>
                    </div>
                @endif

                {{-- Carte 3 : Raccourci Profil --}}
                <a href="{{ route('profile.edit') }}" class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition group cursor-pointer hover:border-indigo-100">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-gray-50 text-gray-600 rounded-xl group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">Configuration</span>
                    </div>
                    <div>
                        <div class="text-lg font-bold text-gray-900">Mon Compte</div>
                        <div class="text-sm text-gray-500 mt-1">Gérer mes informations</div>
                    </div>
                </a>
            </div>

            {{-- 3. Liste des Projets Récents --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/30">
                    <h3 class="font-bold text-lg text-gray-900">Dossiers récemment mis à jour</h3>
                    <a href="{{ route('forms.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 transition">
                        Voir tous les dossiers &rarr;
                    </a>
                </div>

                @php
                    $recentForms = Auth::user()->forms()->latest()->take(5)->get();
                @endphp

                @if($recentForms->count() > 0)
                    <div class="divide-y divide-gray-100">
                        @foreach($recentForms as $form)
                            <div class="p-4 hover:bg-gray-50 transition duration-150 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="flex items-start gap-4">
                                    <div class="flex-shrink-0 mt-1">
                                        <div class="h-10 w-10 bg-indigo-50 text-indigo-600 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-semibold text-gray-900 line-clamp-1" title="{{ $form->name }}">
                                            {{ $form->name }}
                                        </h4>
                                        <div class="flex items-center gap-3 mt-1 text-xs text-gray-500">
                                            <span class="flex items-center">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                {{ $form->updated_at->diffForHumans() }}
                                            </span>
                                            <span>&bull;</span>
                                            <span>{{ $form->fields->count() }} champs</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between sm:justify-end gap-3 w-full sm:w-auto mt-2 sm:mt-0">
                                    @if($form->is_active)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-100">
                                            Actif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200">
                                            Brouillon
                                        </span>
                                    @endif

                                    <a href="{{ route('forms.show', $form) }}" class="text-gray-400 hover:text-indigo-600 transition p-2 hover:bg-indigo-50 rounded-full" title="Voir">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-16">
                        <div class="h-16 w-16 bg-gray-50 text-gray-300 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <h3 class="text-gray-900 font-medium text-lg">Aucun dossier trouvé</h3>
                        <p class="text-gray-500 mt-1 mb-6">Commencez par créer votre premier cahier des charges.</p>
                        <a href="{{ route('forms.create') }}" class="inline-flex items-center px-5 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition shadow-md">
                            Créer un dossier
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
