<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('forms.show', $form) }}" class="mr-4 text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Modifier le cahier des charges</h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition
                     class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded">
                    <div class="flex items-center justify-between">
                        <span>{{ session('success') }}</span>
                        <button @click="show = false">✕</button>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded">
                    <p class="font-bold">Erreurs de validation :</p>
                    <ul class="list-disc list-inside mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @php
                $cdc = $form->cdcs()->first();
                $cdcData = $cdc ? $cdc->data : [];
                $getValue = function($key, $default = '') use ($cdcData) {
                    return old($key, $cdcData[$key] ?? $default);
                };
            @endphp

            <form method="POST" action="{{ route('forms.update', $form) }}" x-data="cdcFormBuilder()" class="space-y-6">
                @csrf
                @method('PUT')

                <input type="hidden" name="name" value="{{ old('name', $form->name) }}">
                <input type="hidden" name="description" value="{{ old('description', $form->description) }}">
                <input type="hidden" name="is_active" value="{{ old('is_active', $form->is_active ? '1' : '0') }}">

                <!-- Section 1: INFORMATIONS GÉNÉRALES -->
                <div class="bg-white shadow-sm rounded-lg">
                    <div class="p-6 border-b border-gray-200 bg-indigo-50">
                        <h3 class="text-lg font-bold text-indigo-900">1. INFORMATIONS GÉNÉRALES</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nom du candidat *</label>
                                <input type="text" name="candidat_nom" required value="{{ $getValue('candidat_nom') }}" placeholder="Dupont"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Prénom du candidat *</label>
                                <input type="text" name="candidat_prenom" required value="{{ $getValue('candidat_prenom') }}" placeholder="Jean"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Lieu de travail *</label>
                            <input type="text" name="lieu_travail" required value="{{ $getValue('lieu_travail') }}" placeholder="Ex: ETML, Lausanne"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Orientation *</label>
                            <div class="space-y-2">
                                @php $currentOrientation = $getValue('orientation'); @endphp
                                @foreach([
                                    '88601 Développement d\'applications',
                                    '88602 Informatique d\'entreprise',
                                    '88603 Technique des systèmes',
                                    '88614 Informaticienne d\'entreprise CFC'
                                ] as $orientation)
                                    <label class="flex items-center">
                                        <input type="radio" name="orientation" value="{{ $orientation }}"
                                               {{ $currentOrientation == $orientation ? 'checked' : '' }} class="text-indigo-600">
                                        <span class="ml-2 text-sm">{{ $orientation }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Chef de projet -->
                        <div class="border-t pt-4 mt-4">
                            <h4 class="font-semibold text-gray-800 mb-3">Chef de projet</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nom *</label>
                                    <input type="text" name="chef_projet_nom" required value="{{ $getValue('chef_projet_nom') }}" placeholder="Martin"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Prénom *</label>
                                    <input type="text" name="chef_projet_prenom" required value="{{ $getValue('chef_projet_prenom') }}" placeholder="Sophie"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                                    <input type="email" name="chef_projet_email" required value="{{ $getValue('chef_projet_email') }}" placeholder="sophie.martin@example.com"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone *</label>
                                    <input type="tel" name="chef_projet_telephone" required value="{{ $getValue('chef_projet_telephone', '+41 ') }}"
                                           pattern="\+41\s[0-9]{2}\s[0-9]{3}\s[0-9]{2}\s[0-9]{2}" placeholder="+41 21 123 45 67"
                                           title="Format suisse : +41 XX XXX XX XX" maxlength="16" oninput="formatSwissPhone(this)"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>
                        </div>

                        <!-- Expert 1 -->
                        <div class="border-t pt-4">
                            <h4 class="font-semibold text-gray-800 mb-3">Expert 1</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nom *</label>
                                    <input type="text" name="expert1_nom" required value="{{ $getValue('expert1_nom') }}" placeholder="Durand"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Prénom *</label>
                                    <input type="text" name="expert1_prenom" required value="{{ $getValue('expert1_prenom') }}" placeholder="Pierre"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                                    <input type="email" name="expert1_email" required value="{{ $getValue('expert1_email') }}" placeholder="pierre.durand@example.com"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone *</label>
                                    <input type="tel" name="expert1_telephone" required value="{{ $getValue('expert1_telephone', '+41 ') }}"
                                           pattern="\+41\s[0-9]{2}\s[0-9]{3}\s[0-9]{2}\s[0-9]{2}" placeholder="+41 21 123 45 67"
                                           title="Format suisse : +41 XX XXX XX XX" maxlength="16" oninput="formatSwissPhone(this)"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>
                        </div>

                        <!-- Expert 2 -->
                        <div class="border-t pt-4">
                            <h4 class="font-semibold text-gray-800 mb-3">Expert 2</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nom *</label>
                                    <input type="text" name="expert2_nom" required value="{{ $getValue('expert2_nom') }}" placeholder="Blanc"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Prénom *</label>
                                    <input type="text" name="expert2_prenom" required value="{{ $getValue('expert2_prenom') }}" placeholder="Marie"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                                    <input type="email" name="expert2_email" required value="{{ $getValue('expert2_email') }}" placeholder="marie.blanc@example.com"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone *</label>
                                    <input type="tel" name="expert2_telephone" required value="{{ $getValue('expert2_telephone', '+41 ') }}"
                                           pattern="\+41\s[0-9]{2}\s[0-9]{3}\s[0-9]{2}\s[0-9]{2}" placeholder="+41 21 123 45 67"
                                           title="Format suisse : +41 XX XXX XX XX" maxlength="16" oninput="formatSwissPhone(this)"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>
                        </div>

                        <!-- Période et horaire -->
                        <div class="border-t pt-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4" x-data="{
                                totalHours: @json((int) old('nombre_heures', $getValue('nombre_heures', '90')))
                            }">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Période de réalisation *</label>
                                    <div class="grid grid-cols-2 gap-2">
                                        <div>
                                            <label class="text-xs text-gray-500">Du</label>
                                            <input type="date" name="date_debut" required value="{{ $getValue('date_debut', $prefillData['date_debut'] ?? '') }}"
                                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                        </div>
                                        <div>
                                            <label class="text-xs text-gray-500">Au</label>
                                            <input type="date" name="date_fin" required value="{{ $getValue('date_fin', $prefillData['date_fin'] ?? '') }}"
                                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Horaire de travail *</label>
                                    <div class="space-y-1">
                                        <div>
                                            <label class="text-xs text-gray-500">Matin</label>
                                            <div class="flex gap-1 items-center">
                                                <input type="time" name="heure_matin_debut" required value="{{ $getValue('heure_matin_debut', $prefillData['heure_matin_debut'] ?? '08:30') }}"
                                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                                <span class="text-xs">–</span>
                                                <input type="time" name="heure_matin_fin" required value="{{ $getValue('heure_matin_fin', $prefillData['heure_matin_fin'] ?? '12:30') }}"
                                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="text-xs text-gray-500">Après-midi</label>
                                            <div class="flex gap-1 items-center">
                                                <input type="time" name="heure_aprem_debut" required value="{{ $getValue('heure_aprem_debut', $prefillData['heure_aprem_debut'] ?? '13:30') }}"
                                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                                <span class="text-xs">–</span>
                                                <input type="time" name="heure_aprem_fin" required value="{{ $getValue('heure_aprem_fin', $prefillData['heure_aprem_fin'] ?? '17:30') }}"
                                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <input type="hidden" name="nombre_heures" x-model="totalHours" value="90">
                            </div>
                        </div>

                        <!-- Planning Section -->
                        <div class="border-t pt-4" x-data="planningCalculatorEdit({
                            total_heures: '{{ old('nombre_heures', $getValue('nombre_heures', '90')) }}',
                            planning_analyse: '{{ old('planning_analyse', $getValue('planning_analyse', '15%')) }}',
                            planning_implementation: '{{ old('planning_implementation', $getValue('planning_implementation', '50%')) }}',
                            planning_tests: '{{ old('planning_tests', $getValue('planning_tests', '20%')) }}',
                            planning_documentation: '{{ old('planning_documentation', $getValue('planning_documentation', '15%')) }}'
                        })" @init="init()">
                            <h4 class="font-semibold text-gray-800 mb-4 flex items-center justify-between">
                                <span>Répartition du planning</span>
                                <div class="flex gap-2">
                                    <button type="button"
                                            @click="switchMode('pourcentage')"
                                            :class="mode === 'pourcentage' ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700'"
                                            class="px-4 py-2 rounded-md text-sm font-medium transition-all duration-200 hover:shadow-md">
                                        % (100%)
                                    </button>
                                    <button type="button"
                                            @click="switchMode('heures')"
                                            :class="mode === 'heures' ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700'"
                                            class="px-4 py-2 rounded-md text-sm font-medium transition-all duration-200 hover:shadow-md">
                                        Heures (<span x-text="totalHeures"></span>h)
                                    </button>
                                </div>
                            </h4>

                            <!-- Affichage du total et du mode -->
                            <div class="mb-6 p-4 rounded-lg"
                                 :class="isValid ? 'bg-green-50 border-l-4 border-green-500' : 'bg-orange-50 border-l-4 border-orange-500'">
                                <div class="flex justify-between items-center">
                                    <span class="font-semibold text-gray-800">Total:</span>
                                    <div class="text-right">
                                        <span class="text-2xl font-bold"
                                              :class="isValid ? 'text-green-600' : 'text-orange-600'"
                                              x-text="total + (mode === 'heures' ? 'h' : '%')"></span>
                                        <span class="text-xs text-gray-500 ml-2"
                                              x-show="mode === 'heures'"
                                              x-text="'= ' + totalPercent + '%'"></span>
                                        <span class="text-xs text-gray-500 ml-2"
                                              x-show="mode === 'pourcentage'"
                                              x-text="'= ' + percentToHeures(total) + 'h'"></span>
                                    </div>
                                </div>
                                <div class="mt-3 text-sm font-medium"
                                     :class="isValid ? 'text-green-700' : 'text-orange-700'"
                                     x-text="validationMessage">
                                </div>
                            </div>

                            <!-- Grille des sliders -->
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                                <!-- Analyse -->
                                <div class="bg-white rounded-lg p-4 border border-gray-200 hover:border-indigo-300 transition">
                                    <div class="flex items-baseline justify-between mb-3">
                                        <label class="block text-sm font-semibold text-gray-700">Analyse</label>
                                        <span class="text-sm font-bold text-indigo-600"
                                              x-text="analyse + (mode === 'heures' ? 'h' : '%')"></span>
                                    </div>

                                    <input type="range"
                                           x-model.number="analyse"
                                           :min="0"
                                           :max="getMax()"
                                           :step="mode === 'heures' ? 1 : 1"
                                           @input="analyse = clampValue($event.target.value); autoAdjustForPercent()"
                                           class="w-full h-2 bg-gray-300 rounded-lg appearance-none cursor-pointer accent-indigo-600">

                                    <div class="flex items-center gap-2 mt-3">
                                        <input type="number"
                                               x-model.number="analyse"
                                               :min="0"
                                               :max="getMax()"
                                               @change="analyse = clampValue($event.target.value); autoAdjustForPercent()"
                                               class="w-16 px-2 py-1 text-sm border border-gray-300 rounded focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                        <span class="text-xs text-gray-500" x-text="mode === 'heures' ? 'heures' : '%'"></span>
                                    </div>

                                    <input type="hidden" name="planning_analyse" :value="formatValue(analyse)">
                                </div>

                                <!-- Implémentation -->
                                <div class="bg-white rounded-lg p-4 border border-gray-200 hover:border-green-300 transition">
                                    <div class="flex items-baseline justify-between mb-3">
                                        <label class="block text-sm font-semibold text-gray-700">Implémentation</label>
                                        <span class="text-sm font-bold text-green-600"
                                              x-text="implementation + (mode === 'heures' ? 'h' : '%')"></span>
                                    </div>

                                    <input type="range"
                                           x-model.number="implementation"
                                           :min="0"
                                           :max="getMax()"
                                           :step="mode === 'heures' ? 1 : 1"
                                           @input="implementation = clampValue($event.target.value); autoAdjustForPercent()"
                                           class="w-full h-2 bg-gray-300 rounded-lg appearance-none cursor-pointer accent-green-600">

                                    <div class="flex items-center gap-2 mt-3">
                                        <input type="number"
                                               x-model.number="implementation"
                                               :min="0"
                                               :max="getMax()"
                                               @change="implementation = clampValue($event.target.value); autoAdjustForPercent()"
                                               class="w-16 px-2 py-1 text-sm border border-gray-300 rounded focus:border-green-500 focus:ring-1 focus:ring-green-500">
                                        <span class="text-xs text-gray-500" x-text="mode === 'heures' ? 'heures' : '%'"></span>
                                    </div>

                                    <input type="hidden" name="planning_implementation" :value="formatValue(implementation)">
                                </div>

                                <!-- Tests -->
                                <div class="bg-white rounded-lg p-4 border border-gray-200 hover:border-orange-300 transition">
                                    <div class="flex items-baseline justify-between mb-3">
                                        <label class="block text-sm font-semibold text-gray-700">Tests</label>
                                        <span class="text-sm font-bold text-orange-600"
                                              x-text="tests + (mode === 'heures' ? 'h' : '%')"></span>
                                    </div>

                                    <input type="range"
                                           x-model.number="tests"
                                           :min="0"
                                           :max="getMax()"
                                           :step="mode === 'heures' ? 1 : 1"
                                           @input="tests = clampValue($event.target.value); autoAdjustForPercent()"
                                           class="w-full h-2 bg-gray-300 rounded-lg appearance-none cursor-pointer accent-orange-600">

                                    <div class="flex items-center gap-2 mt-3">
                                        <input type="number"
                                               x-model.number="tests"
                                               :min="0"
                                               :max="getMax()"
                                               @change="tests = clampValue($event.target.value); autoAdjustForPercent()"
                                               class="w-16 px-2 py-1 text-sm border border-gray-300 rounded focus:border-orange-500 focus:ring-1 focus:ring-orange-500">
                                        <span class="text-xs text-gray-500" x-text="mode === 'heures' ? 'heures' : '%'"></span>
                                    </div>

                                    <input type="hidden" name="planning_tests" :value="formatValue(tests)">
                                </div>

                                <!-- Documentation -->
                                <div class="bg-white rounded-lg p-4 border border-gray-200 hover:border-purple-300 transition">
                                    <div class="flex items-baseline justify-between mb-3">
                                        <label class="block text-sm font-semibold text-gray-700">Documentation</label>
                                        <span class="text-sm font-bold text-purple-600"
                                              x-text="documentation + (mode === 'heures' ? 'h' : '%')"></span>
                                    </div>

                                    <input type="range"
                                           x-model.number="documentation"
                                           :min="0"
                                           :max="getMax()"
                                           :step="mode === 'heures' ? 1 : 1"
                                           @input="documentation = clampValue($event.target.value); autoAdjustForPercent()"
                                           class="w-full h-2 bg-gray-300 rounded-lg appearance-none cursor-pointer accent-purple-600">

                                    <div class="flex items-center gap-2 mt-3">
                                        <input type="number"
                                               x-model.number="documentation"
                                               :min="0"
                                               :max="getMax()"
                                               @change="documentation = clampValue($event.target.value); autoAdjustForPercent()"
                                               class="w-16 px-2 py-1 text-sm border border-gray-300 rounded focus:border-purple-500 focus:ring-1 focus:ring-purple-500">
                                        <span class="text-xs text-gray-500" x-text="mode === 'heures' ? 'heures' : '%'"></span>
                                    </div>

                                    <input type="hidden" name="planning_documentation" :value="formatValue(documentation)">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 2: PROCÉDURE -->
                <div class="bg-white shadow-sm rounded-lg">
                    <div class="p-6 border-b border-gray-200 bg-indigo-50">
                        <h3 class="text-lg font-bold text-indigo-900">2. PROCÉDURE</h3>
                    </div>
                    <div class="p-6">
                        <x-markdown-editor name="procedure" :value="$getValue('procedure', '')" label="Description de la procédure"
                                           placeholder="Points de la procédure..." help="Utilisez Markdown pour formater votre texte" :rows="12" />
                    </div>
                </div>

                <!-- Section 3: TITRE -->
                <div class="bg-white shadow-sm rounded-lg">
                    <div class="p-6 border-b border-gray-200 bg-indigo-50">
                        <h3 class="text-lg font-bold text-indigo-900">3. TITRE</h3>
                    </div>
                    <div class="p-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Titre du projet *</label>
                        <input type="text" name="titre_projet" required value="{{ $getValue('titre_projet') }}"
                               placeholder="Ex: Skoob - Logiciel pour l'exploitation des librairies"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>

                <!-- Section 4: MATÉRIEL ET LOGICIEL -->
                <div class="bg-white shadow-sm rounded-lg">
                    <div class="p-6 border-b border-gray-200 bg-indigo-50">
                        <h3 class="text-lg font-bold text-indigo-900">4. MATÉRIEL ET LOGICIEL À DISPOSITION</h3>
                    </div>
                    <div class="p-6">
                        <x-markdown-editor name="materiel_logiciel" :value="$getValue('materiel_logiciel', '')" label="Matériel et logiciels"
                                           placeholder="- 1 PC en configuration standard..." help="Utilisez des listes Markdown" :rows="6" />
                    </div>
                </div>

                <!-- Section 5: PRÉREQUIS -->
                <div class="bg-white shadow-sm rounded-lg">
                    <div class="p-6 border-b border-gray-200 bg-indigo-50">
                        <h3 class="text-lg font-bold text-indigo-900">5. PRÉREQUIS</h3>
                    </div>
                    <div class="p-6">
                        <x-markdown-editor name="prerequis" :value="$getValue('prerequis', '')" label="Prérequis"
                                           placeholder="- Connaissances du développement orienté objet..." help="Utilisez des listes Markdown" :rows="4" />
                    </div>
                </div>

                <!-- Section 6: DESCRIPTIF DU PROJET -->
                <div class="bg-white shadow-sm rounded-lg">
                    <div class="p-6 border-b border-gray-200 bg-indigo-50">
                        <h3 class="text-lg font-bold text-indigo-900">6. DESCRIPTIF DU PROJET</h3>
                    </div>
                    <div class="p-6">
                        <x-markdown-editor name="descriptif_projet" :value="$getValue('descriptif_projet', '')" label="Description complète du projet"
                                           placeholder="Le projet consiste à réaliser une application..." help="Utilisez Markdown pour structurer votre texte" :rows="15" required />
                    </div>
                </div>

                <!-- Section 7: LIVRABLES -->
                <div class="bg-white shadow-sm rounded-lg">
                    <div class="p-6 border-b border-gray-200 bg-indigo-50">
                        <h3 class="text-lg font-bold text-indigo-900">7. LIVRABLES</h3>
                    </div>
                    <div class="p-6">
                        <x-markdown-editor name="livrables" :value="$getValue('livrables', '')" label="Livrables attendus"
                                           placeholder="- Rapport de projet..." help="Utilisez des listes Markdown" :rows="6" />
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="flex justify-end gap-4">
                    <a href="{{ route('forms.show', $form) }}" class="px-6 py-3 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition">Annuler</a>
                    <button type="submit" class="px-6 py-3 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition font-medium shadow-lg">
                        Mettre à jour le cahier des charges
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/phone-formatter.js') }}"></script>
</x-app-layout>
