<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-2xl font-bold mb-2">Bienvenue, {{ Auth::user()->name }} !</h3>
                            <p class="text-gray-600">{{ now()->locale('fr')->isoFormat('dddd, D MMMM YYYY') }}</p>
                        </div>

                        <div class="text-right">
                            @foreach(Auth::user()->getRoleNames() as $role)
                                @php
                                    $roleConfig = [
                                        'super-admin' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'label' => 'Super Admin'],
                                        'admin' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-800', 'label' => 'Admin'],
                                        'formateur' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'label' => 'Formateur'],
                                        'user' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'label' => 'Utilisateur'],
                                        'guest' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => 'Invité'],
                                    ];
                                    $config = $roleConfig[$role] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => ucfirst($role)];
                                @endphp
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium {{ $config['bg'] }} {{ $config['text'] }}">
                                    {{ $config['label'] }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                            <div class="ml-5">
                                <p class="text-2xl font-semibold text-gray-900">{{ Auth::user()->forms()->count() }}</p>
                                <p class="text-sm text-gray-500">Mes formulaires</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-5">
                                <p class="text-2xl font-semibold text-gray-900">{{ Auth::user()->cdcs()->count() }}</p>
                                <p class="text-sm text-gray-500">CDC générés</p>
                            </div>
                        </div>
                    </div>
                </div>

                @if(Auth::user()->hasAnyRole(['admin', 'super-admin']))
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-5">
                                    <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\User::count() }}</p>
                                    <p class="text-sm text-gray-500">Utilisateurs</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path>
                                    </svg>
                                </div>
                                <div class="ml-5">
                                    <p class="text-2xl font-semibold text-gray-900">{{ Auth::user()->forms()->where('is_active', true)->count() }}</p>
                                    <p class="text-sm text-gray-500">Modèles actifs</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                <a href="{{ route('forms.index') }}" class="group">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-all duration-200 transform hover:-translate-y-1">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900 group-hover:text-green-600">
                                        Formulaires
                                    </h4>
                                    <p class="mt-1 text-sm text-gray-600">
                                        Créer et éditer des formulaires
                                    </p>
                                </div>
                                <svg class="h-6 w-6 text-gray-400 group-hover:text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </a>

                <a href="{{ route('cdcs.index') }}" class="group">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-all duration-200 transform hover:-translate-y-1">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900 group-hover:text-blue-600">
                                        Mes CDC
                                    </h4>
                                    <p class="mt-1 text-sm text-gray-600">
                                        Cahiers des charges générés
                                    </p>
                                </div>
                                <svg class="h-6 w-6 text-gray-400 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </a>

                @if(Auth::user()->hasAnyRole(['admin', 'super-admin']))
                @else
                    <a href="{{ route('profile.edit') }}" class="group">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-all duration-200 transform hover:-translate-y-1">
                            <div class="p-6">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="text-lg font-semibold text-gray-900 group-hover:text-indigo-600">
                                            Mon Profil
                                        </h4>
                                        <p class="mt-1 text-sm text-gray-600">
                                            Gérer mes informations
                                        </p>
                                    </div>
                                    <svg class="h-6 w-6 text-gray-400 group-hover:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </a>
                @endif
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions Rapides</h3>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('forms.create') }}"
                           class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Nouveau Formulaire
                        </a>

                        <a href="{{ route('forms.index') }}"
                           class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                            </svg>
                            Voir mes formulaires
                        </a>

                        <a href="{{ route('cdcs.index') }}"
                           class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Voir mes CDC
                        </a>
                        @if(Auth::user()->hasAnyRole(['admin', 'super-admin']))
                        @endif
                    </div>
                </div>
            </div>

            @php
                $recentForms = Auth::user()->forms()->latest()->take(5)->get();
            @endphp

            @if($recentForms->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Mes derniers formulaires</h3>
                            <a href="{{ route('forms.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900">
                                Voir tout
                            </a>
                        </div>

                        <div class="space-y-3">
                            @foreach($recentForms as $form)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                    <div class="flex-1">
                                        <h4 class="font-medium text-gray-900">{{ $form->name }}</h4>
                                        <div class="flex items-center gap-3 text-sm text-gray-500 mt-1">
                                            <span>{{ $form->fields->count() }} champs</span>
                                            <span>•</span>
                                            <span>{{ $form->created_at->diffForHumans() }}</span>
                                            @if($form->is_active)
                                                <span class="px-2 py-0.5 text-xs bg-green-100 text-green-800 rounded">Actif</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex gap-2">
                                        <a href="{{ route('forms.show', $form) }}"
                                           class="text-indigo-600 hover:text-indigo-900 text-sm px-3 py-1 bg-indigo-50 rounded hover:bg-indigo-100 transition">
                                            Voir
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun formulaire</h3>
                        <p class="mt-1 text-sm text-gray-500">Commencez par créer votre premier formulaire.</p>
                        <div class="mt-6">
                            <a href="{{ route('forms.create') }}"
                               class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                <svg class="h-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Créer mon premier formulaire
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
