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

            @if (! Auth::user()->hasVerifiedEmail())
                <div class="bg-amber-50 border-l-4 border-amber-400 p-4 mb-4 rounded-r-lg shadow-sm">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-amber-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3 w-full">
                            <h3 class="text-sm font-medium text-amber-800">Action requise</h3>
                            <div class="mt-1 text-sm text-amber-700">
                                <p>
                                    Votre adresse email n'est pas encore vérifiée. Vous ne pouvez pas créer de nouveaux cahiers des charges tant que votre compte n'est pas validé.
                                </p>
                            </div>
                            <div class="mt-4">
                                <form method="POST" action="{{ route('verification.send') }}">
                                    @csrf
                                    <button type="submit" class="text-sm font-medium text-amber-800 underline hover:text-amber-900">
                                        Renvoyer le mail de vérification
                                    </button>
                                </form>
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
                        <button disabled class="flex-1 md:flex-none opacity-50 cursor-not-allowed inline-flex items-center justify-center px-6 py-3 bg-gray-400 text-white text-sm font-medium rounded-xl" title="Vérifiez votre email pour débloquer">
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
