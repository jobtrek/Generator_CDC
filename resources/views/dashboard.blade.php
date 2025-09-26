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
                            <h3 class="text-2xl font-bold mb-2">Bienvenue, {{ Auth::user()->name }} ! 👋</h3>
                            <p class="text-gray-600">{{ now()->format('l, d F Y') }}</p>
                        </div>

                        <div class="text-right">
                            @foreach(Auth::user()->getRoleNames() as $role)
                                @php
                                    $roleConfig = [
                                        'super-admin' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'icon' => '👑'],
                                        'admin' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-800', 'icon' => '🛡️'],
                                        'manager' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'icon' => '👔'],
                                        'user' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'icon' => '👤'],
                                        'guest' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'icon' => '👁️']
                                    ];
                                    $config = $roleConfig[$role] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'icon' => '👤'];
                                @endphp
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium {{ $config['bg'] }} {{ $config['text'] }}">
                                    <span class="text-lg mr-2">{{ $config['icon'] }}</span>
                                    {{ ucfirst($role) }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                @can('cdc.view')
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div class="ml-5">
                                    <p class="text-2xl font-semibold text-gray-900">0</p>
                                    <p class="text-sm text-gray-500">CDC créés</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endcan

                @can('form.view')
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                </div>
                                <div class="ml-5">
                                    <p class="text-2xl font-semibold text-gray-900">0</p>
                                    <p class="text-sm text-gray-500">Formulaires</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endcan

                @can('template.view')
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div class="ml-5">
                                    <p class="text-2xl font-semibold text-gray-900">3</p>
                                    <p class="text-sm text-gray-500">Templates</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endcan

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
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                @can('cdc.view')
                    <a href="{{ route('cdc.index') }}" class="group">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-all duration-200 transform hover:-translate-y-1">
                            <div class="p-6">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="text-lg font-semibold text-gray-900 group-hover:text-blue-600">
                                            📄 Cahiers des Charges
                                        </h4>
                                        <p class="mt-1 text-sm text-gray-600">
                                            Gérer vos CDC
                                        </p>
                                    </div>
                                    <svg class="h-6 w-6 text-gray-400 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </a>
                @endcan

                @can('form.view')
                    <a href="{{ route('forms.index') }}" class="group">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-all duration-200 transform hover:-translate-y-1">
                            <div class="p-6">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="text-lg font-semibold text-gray-900 group-hover:text-green-600">
                                            📝 Formulaires
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
                @endcan

                @can('template.view')
                    <a href="{{ route('templates.index') }}" class="group">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-all duration-200 transform hover:-translate-y-1">
                            <div class="p-6">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="text-lg font-semibold text-gray-900 group-hover:text-purple-600">
                                            📋 Templates
                                        </h4>
                                        <p class="mt-1 text-sm text-gray-600">
                                            Gérer les modèles Word
                                        </p>
                                    </div>
                                    <svg class="h-6 w-6 text-gray-400 group-hover:text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </a>
                @endcan

                @if(Auth::user()->hasAnyRole(['admin', 'super-admin']))
                    <a href="{{ route('admin.dashboard') }}" class="group">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-all duration-200 transform hover:-translate-y-1">
                            <div class="p-6">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="text-lg font-semibold text-gray-900 group-hover:text-red-600">
                                            ⚙️ Administration
                                        </h4>
                                        <p class="mt-1 text-sm text-gray-600">
                                            Panneau d'administration
                                        </p>
                                    </div>
                                    <svg class="h-6 w-6 text-gray-400 group-hover:text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </a>
                @endif

                <a href="{{ route('profile.edit') }}" class="group">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-all duration-200 transform hover:-translate-y-1">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900 group-hover:text-indigo-600">
                                        👤 Mon Profil
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

                <a href="#" class="group">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-all duration-200 transform hover:-translate-y-1">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900 group-hover:text-yellow-600">
                                        📚 Documentation
                                    </h4>
                                    <p class="mt-1 text-sm text-gray-600">
                                        Guides et aide
                                    </p>
                                </div>
                                <svg class="h-6 w-6 text-gray-400 group-hover:text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">🚀 Actions Rapides</h3>
                    <div class="flex flex-wrap gap-3">
                        @can('cdc.create')
                            <a href="{{ route('cdc.create') }}"
                               class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Nouveau CDC
                            </a>
                        @endcan

                        @can('form.create')
                            <a href="{{ route('forms.create') }}"
                               class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Nouveau Formulaire
                            </a>
                        @endcan

                        @can('template.create')
                            <a href="{{ route('templates.create') }}"
                               class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 focus:bg-purple-700 active:bg-purple-900 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Nouveau Template
                            </a>
                        @endcan
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <details class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <summary class="p-6 cursor-pointer hover:bg-gray-50">
                        <span class="text-sm font-medium text-gray-700">🔑 Voir mes permissions détaillées</span>
                    </summary>
                    <div class="px-6 pb-6">
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2">
                            @foreach(Auth::user()->getAllPermissions()->sortBy('name') as $permission)
                                @php
                                    $category = explode('.', $permission->name)[0];
                                    $categoryColors = [
                                        'cdc' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'form' => 'bg-green-50 text-green-700 border-green-200',
                                        'template' => 'bg-purple-50 text-purple-700 border-purple-200',
                                        'user' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                        'dashboard' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                        'settings' => 'bg-red-50 text-red-700 border-red-200',
                                        'logs' => 'bg-gray-50 text-gray-700 border-gray-200',
                                    ];
                                    $colorClass = $categoryColors[$category] ?? 'bg-gray-50 text-gray-700 border-gray-200';
                                @endphp
                                <span class="text-xs px-2 py-1 rounded border {{ $colorClass }}">
                                    {{ $permission->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </details>
            </div>
        </div>
    </div>
</x-app-layout>
