<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center">
                <a href="{{ route('forms.index') }}" class="mr-4 text-gray-600 hover:text-gray-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $form->name }}
                </h2>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('cdcs.create', ['form_id' => $form->id]) }}"
                   class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Générer un CDC
                </a>

                <a href="{{ route('forms.edit', $form) }}"
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Modifier
                </a>

                <form method="POST"
                      action="{{ route('forms.destroy', $form) }}"
                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce formulaire ?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Supprimer
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

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

            @php
                // Récupérer les données du CDC associé
                $cdc = $form->cdcs()->first();
                $cdcData = $cdc ? $cdc->data : [];

                // Fonction helper pour récupérer les valeurs
                $getValue = function($key, $default = 'Non renseigné') use ($cdcData) {
                    return $cdcData[$key] ?? $default;
                };
            @endphp

                <!-- Informations du formulaire -->
            <div class="bg-white shadow-sm rounded-lg mb-6">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-indigo-700 flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Informations du formulaire
                    </h3>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <dt class="text-sm font-medium text-gray-500 flex items-center mb-2">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                                </svg>
                                Description
                            </dt>
                            <dd class="text-sm text-gray-900">
                                {{ $form->description ?? 'Aucune description' }}
                            </dd>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <dt class="text-sm font-medium text-gray-500 flex items-center mb-2">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Statut
                            </dt>
                            <dd>
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
                            </dd>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <dt class="text-sm font-medium text-gray-500 flex items-center mb-2">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Créé par
                            </dt>
                            <dd class="text-sm text-gray-900">
                                {{ $form->user->name }}
                            </dd>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <dt class="text-sm font-medium text-gray-500 flex items-center mb-2">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Date de création
                            </dt>
                            <dd class="text-sm text-gray-900">
                                {{ $form->created_at->format('d/m/Y à H:i') }}
                            </dd>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <dt class="text-sm font-medium text-gray-500 flex items-center mb-2">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Dernière modification
                            </dt>
                            <dd class="text-sm text-gray-900">
                                {{ $form->updated_at->format('d/m/Y à H:i') }}
                            </dd>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <dt class="text-sm font-medium text-gray-500 flex items-center mb-2">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                Nombre de champs
                            </dt>
                            <dd>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $form->fields->count() }} champ{{ $form->fields->count() > 1 ? 's' : '' }}
                                </span>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Section 1: INFORMATIONS GÉNÉRALES -->
            <div class="bg-white shadow-sm rounded-lg mb-6">
                <div class="p-6 border-b border-gray-200 bg-indigo-50">
                    <h3 class="text-lg font-bold text-indigo-900">
                        1. INFORMATIONS GÉNÉRALES
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Candidat -->
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-4 rounded-lg border border-blue-200">
                            <h4 class="font-semibold text-gray-800 mb-3 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Candidat
                            </h4>
                            <dl class="space-y-2">
                                <div>
                                    <dt class="text-xs font-medium text-gray-500">Nom</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ $getValue('candidat_nom') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-medium text-gray-500">Prénom</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ $getValue('candidat_prenom') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-medium text-gray-500">Lieu de travail</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ $getValue('lieu_travail') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-medium text-gray-500">Orientation</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ $getValue('orientation') }}</dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Chef de projet -->
                        <div class="bg-gradient-to-br from-green-50 to-emerald-50 p-4 rounded-lg border border-green-200">
                            <h4 class="font-semibold text-gray-800 mb-3 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                Chef de projet
                            </h4>
                            <dl class="space-y-2">
                                <div>
                                    <dt class="text-xs font-medium text-gray-500">Nom</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ $getValue('chef_projet_nom') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-medium text-gray-500">Prénom</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ $getValue('chef_projet_prenom') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-medium text-gray-500">Email</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ $getValue('chef_projet_email') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-medium text-gray-500">Téléphone</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ $getValue('chef_projet_telephone') }}</dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Expert 1 -->
                        <div class="bg-gradient-to-br from-purple-50 to-pink-50 p-4 rounded-lg border border-purple-200">
                            <h4 class="font-semibold text-gray-800 mb-3 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                </svg>
                                Expert 1
                            </h4>
                            <dl class="space-y-2">
                                <div>
                                    <dt class="text-xs font-medium text-gray-500">Nom</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ $getValue('expert1_nom') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-medium text-gray-500">Prénom</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ $getValue('expert1_prenom') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-medium text-gray-500">Email</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ $getValue('expert1_email') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-medium text-gray-500">Téléphone</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ $getValue('expert1_telephone') }}</dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Expert 2 -->
                        <div class="bg-gradient-to-br from-orange-50 to-red-50 p-4 rounded-lg border border-orange-200">
                            <h4 class="font-semibold text-gray-800 mb-3 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                </svg>
                                Expert 2
                            </h4>
                            <dl class="space-y-2">
                                <div>
                                    <dt class="text-xs font-medium text-gray-500">Nom</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ $getValue('expert2_nom') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-medium text-gray-500">Prénom</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ $getValue('expert2_prenom') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-medium text-gray-500">Email</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ $getValue('expert2_email') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-medium text-gray-500">Téléphone</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ $getValue('expert2_telephone') }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Période et Planning -->
                    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <dt class="text-xs font-medium text-gray-500 mb-1">Période de réalisation</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $getValue('periode_realisation') }}</dd>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <dt class="text-xs font-medium text-gray-500 mb-1">Horaire de travail</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $getValue('horaire_travail') }}</dd>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <dt class="text-xs font-medium text-gray-500 mb-1">Nombre d'heures</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $getValue('nombre_heures') }}</dd>
                        </div>
                    </div>

                    @if($getValue('planning_analyse', '') || $getValue('planning_implementation', ''))
                        <div class="mt-6">
                            <h4 class="font-semibold text-gray-800 mb-3">Planning</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                                    <dt class="text-xs font-medium text-gray-500">Analyse</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ $getValue('planning_analyse', 'Non défini') }}</dd>
                                </div>
                                <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                                    <dt class="text-xs font-medium text-gray-500">Implémentation</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ $getValue('planning_implementation', 'Non défini') }}</dd>
                                </div>
                                <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                                    <dt class="text-xs font-medium text-gray-500">Tests</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ $getValue('planning_tests', 'Non défini') }}</dd>
                                </div>
                                <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                                    <dt class="text-xs font-medium text-gray-500">Documentations</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ $getValue('planning_documentation', 'Non défini') }}</dd>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Section 3: TITRE -->
            <div class="bg-white shadow-sm rounded-lg mb-6">
                <div class="p-6 border-b border-gray-200 bg-indigo-50">
                    <h3 class="text-lg font-bold text-indigo-900">3. TITRE</h3>
                </div>
                <div class="p-6">
                    <p class="text-gray-900 font-medium">{{ $getValue('titre_projet') }}</p>
                </div>
            </div>

            <!-- Section 4: MATÉRIEL ET LOGICIEL -->
            <div class="bg-white shadow-sm rounded-lg mb-6">
                <div class="p-6 border-b border-gray-200 bg-indigo-50">
                    <h3 class="text-lg font-bold text-indigo-900">4. MATÉRIEL ET LOGICIEL À DISPOSITION</h3>
                </div>
                <div class="p-6">
                    @php
                        $materiel = $getValue('materiel_logiciel', '');
                        $materielItems = $materiel ? explode("\n", $materiel) : [];
                    @endphp
                    @if(count($materielItems) > 0)
                        <ul class="list-disc list-inside space-y-1 text-gray-900">
                            @foreach($materielItems as $item)
                                @if(trim($item))
                                    <li>{{ trim($item) }}</li>
                                @endif
                            @endforeach
                        </ul>
                    @else
                        <p class="text-gray-500 italic">Non renseigné</p>
                    @endif
                </div>
            </div>

            <!-- Section 5: PRÉREQUIS -->
            <div class="bg-white shadow-sm rounded-lg mb-6">
                <div class="p-6 border-b border-gray-200 bg-indigo-50">
                    <h3 class="text-lg font-bold text-indigo-900">5. PRÉREQUIS</h3>
                </div>
                <div class="p-6">
                    @php
                        $prerequis = $getValue('prerequis', '');
                        $prerequisItems = $prerequis ? explode("\n", $prerequis) : [];
                    @endphp
                    @if(count($prerequisItems) > 0)
                        <ul class="list-disc list-inside space-y-1 text-gray-900">
                            @foreach($prerequisItems as $item)
                                @if(trim($item))
                                    <li>{{ trim($item) }}</li>
                                @endif
                            @endforeach
                        </ul>
                    @else
                        <p class="text-gray-500 italic">Non renseigné</p>
                    @endif
                </div>
            </div>

                <!-- Section 6: DESCRIPTIF DU PROJET -->
                <div class="bg-white shadow-sm rounded-lg mb-6">
                    <div class="p-6 border-b border-gray-200 bg-indigo-50">
                        <h3 class="text-lg font-bold text-indigo-900">6. DESCRIPTIF DU PROJET</h3>
                    </div>
                    <div class="p-6">
                        @php
                            $descriptif = $getValue('descriptif_projet', '');
                        @endphp
                        @if($descriptif)
                            <div class="prose max-w-none text-gray-900">
                                {!! nl2br(e($descriptif)) !!}
                            </div>
                        @else
                            <p class="text-gray-500 italic">Non renseigné</p>
                        @endif
                    </div>
                </div>

                <!-- Section 7: LIVRABLES -->
                <div class="bg-white shadow-sm rounded-lg mb-6">
                    <div class="p-6 border-b border-gray-200 bg-indigo-50">
                        <h3 class="text-lg font-bold text-indigo-900">7. LIVRABLES</h3>
                    </div>
                    <div class="p-6">
                        @php
                            $livrables = $getValue('livrables', '');
                            $livrablesItems = $livrables ? explode("\n", $livrables) : [];
                        @endphp
                        @if(count($livrablesItems) > 0)
                            <ul class="list-disc list-inside space-y-1 text-gray-900">
                                @foreach($livrablesItems as $item)
                                    @if(trim($item))
                                        <li>{{ trim($item) }}</li>
                                    @endif
                                @endforeach
                            </ul>
                        @else
                            <p class="text-gray-500 italic">Non renseigné</p>
                        @endif
                    </div>
                </div>

                <!-- Champs personnalisés -->
                @php
                    $customFields = $form->fields->where('section', 'custom');
                @endphp

                @if($customFields->count() > 0)
                    <div class="bg-white shadow-sm rounded-lg mb-6">
                        <div class="p-6 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-lg font-bold text-gray-900 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Champs personnalisés supplémentaires
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                @foreach($customFields as $field)
                                    <div class="bg-gradient-to-r from-gray-50 to-blue-50 p-4 rounded-lg border border-gray-200">
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0">
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 font-bold text-sm">
                                            {{ $loop->iteration }}
                                        </span>
                                            </div>
                                            <div class="ml-4 flex-1">
                                                <h4 class="text-md font-semibold text-gray-900 mb-2">
                                                    {{ $field->label }}
                                                    @if($field->is_required)
                                                        <span class="text-red-500 text-sm">*</span>
                                                    @endif
                                                </h4>
                                                <div class="bg-white p-3 rounded border border-gray-200">
                                                    @php
                                                        $customValue = $getValue($field->name, '');
                                                    @endphp
                                                    @if($customValue && $customValue !== 'Non renseigné')
                                                        <p class="text-sm text-gray-900 whitespace-pre-line">{{ $customValue }}</p>
                                                    @else
                                                        <p class="text-sm text-gray-500 italic">Non renseigné</p>
                                                    @endif
                                                </div>
                                                <div class="mt-2 flex items-center text-xs text-gray-500">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                                                    </svg>
                                                    <span class="font-mono">{{ $field->name }}</span>
                                                    <span class="mx-2">•</span>
                                                    <span class="px-2 py-0.5 bg-indigo-100 text-indigo-800 rounded font-medium">
                                                {{ $field->fieldType->name }}
                                            </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

        </div>
    </div>
</x-app-layout>
