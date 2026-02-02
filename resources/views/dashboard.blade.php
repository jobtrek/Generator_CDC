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

            {{-- 1. Message de vérification --}}
            @if (! Auth::user()->hasVerifiedEmail())
                <div class="bg-amber-50 border-l-4 border-amber-400 p-4 mb-4 rounded-r-lg shadow-sm">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-amber-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-amber-700 font-medium">
                                Action requise : Veuillez vérifier votre adresse email.
                                <button type="submit" form="send-verification" class="underline hover:text-amber-800 transition">Renvoyer le lien</button>
                            </p>
                        </div>
                    </div>
                    <form id="send-verification" method="POST" action="{{ route('verification.send') }}">@csrf</form>
                </div>
            @endif

            {{-- 2. KPI Cards - TOUTES CLIQUABLES --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                {{-- Card 1: Total Dossiers --}}
                <a href="{{ route('forms.index') }}" class="block bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition group cursor-pointer">
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
                </a>

                {{-- Card 2: Utilisateurs (Admin) ou Projets actifs (User) --}}
                @if(Auth::user()->hasAnyRole(['admin', 'super-admin']))
                    <a href="{{ route('admin.users.index') }}" class="block bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition group cursor-pointer">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-purple-50 text-purple-600 rounded-xl group-hover:bg-purple-600 group-hover:text-white transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            </div>
                            <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">Admin</span>
                        </div>
                        <div>
                            <div class="text-3xl font-bold text-gray-900">{{ \App\Models\User::count() }}</div>
                            <div class="text-sm text-gray-500 mt-1">Utilisateurs inscrits</div>
                        </div>
                    </a>
                @else
                    <a href="{{ route('forms.index') }}?filter=active" class="block bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition group cursor-pointer">
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
                    </a>
                @endif

                {{-- Card 3: Mon Compte --}}
                <a href="{{ route('profile.edit') }}" class="block bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition group cursor-pointer">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-gray-50 text-gray-600 rounded-xl group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">Profil</span>
                    </div>
                    <div>
                        <div class="text-lg font-bold text-gray-900">Mon Compte</div>
                        <div class="text-sm text-gray-500 mt-1">Gérer mes infos</div>
                    </div>
                </a>
            </div>

            {{-- 3. Section Dossiers Récents --}}
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900">Dossiers récemment mis à jour</h3>
                    <a href="{{ route('forms.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">Voir tout</a>
                </div>

                @php
                    $recentForms = Auth::user()->forms()->latest()->take(4)->get();
                @endphp

                @if($recentForms->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($recentForms as $form)
                            <a href="{{ route('forms.show', $form) }}" class="group bg-white p-5 rounded-2xl shadow-sm border border-gray-100 hover:border-indigo-300 hover:shadow-md transition-all flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="p-3 bg-gray-50 text-gray-400 rounded-xl group-hover:bg-indigo-50 group-hover:text-indigo-600 transition-colors">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-gray-900 group-hover:text-indigo-600 transition-colors">{{ $form->title }}</h4>
                                        <p class="text-xs text-gray-400">Modifié {{ $form->updated_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <div class="text-gray-300 group-hover:text-indigo-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-16">
                        <div class="h-16 w-16 bg-gray-50 text-gray-300 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <h3 class="text-sm font-medium text-gray-900">Aucun dossier</h3>
                        <p class="mt-1 text-sm text-gray-500">Commencez par créer votre premier cahier des charges.</p>
                        <div class="mt-6">
                            <a href="{{ route('forms.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition shadow-lg shadow-indigo-500/20">
                                Créer un cahier des charges
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
