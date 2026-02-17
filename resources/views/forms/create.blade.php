<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('forms.index') }}" class="mr-4 text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Créer un nouveau cahier des charges
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            @if(session('info'))
                <div x-data="{ show: true }" x-show="show" x-transition
                     class="mb-4 p-4 bg-blue-100 border-l-4 border-blue-500 text-blue-700 rounded">
                    <div class="flex items-center justify-between">
                        <span>{{ session('info') }}</span>
                        <button @click="show = false">✕</button>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>{{ session('error') }}</span>
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

            <form method="POST" action="{{ route('forms.store') }}" x-data="{ ...cdcFormBuilder(), submitting: false }" x-on:submit="if(submitting) { $event.preventDefault(); return; } submitting = true;" class="space-y-6">
                @csrf

                <!-- Section 1: INFORMATIONS GÉNÉRALES -->
                <div class="bg-white shadow-sm rounded-lg">
                    <div class="p-6 border-b border-gray-200 bg-indigo-50">
                        <h3 class="text-lg font-bold text-indigo-900">
                            1. INFORMATIONS GÉNÉRALES
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Nom du candidat *
                                </label>
                                <input type="text" name="candidat_nom" required
                                       value="{{ old('candidat_nom') }}"
                                       placeholder="Dupont"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Prénom du candidat *
                                </label>
                                <input type="text" name="candidat_prenom" required
                                       value="{{ old('candidat_prenom') }}"
                                       placeholder="Jean"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Lieu de travail *
                            </label>
                            <input type="text" name="lieu_travail" required
                                   value="{{ old('lieu_travail') }}"
                                   placeholder="Ex: ETML, Lausanne"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Orientation *
                            </label>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="radio" name="orientation" value="88601 Développement d'applications"
                                           {{ old('orientation', '88601 Développement d\'applications') == '88601 Développement d\'applications' ? 'checked' : '' }}
                                           class="text-indigo-600">
                                    <span class="ml-2 text-sm">88601 Développement d'applications</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="orientation" value="88602 Informatique d'entreprise"
                                           {{ old('orientation') == '88602 Informatique d\'entreprise' ? 'checked' : '' }}
                                           class="text-indigo-600">
                                    <span class="ml-2 text-sm">88602 Informatique d'entreprise</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="orientation" value="88603 Technique des systèmes"
                                           {{ old('orientation') == '88603 Technique des systèmes' ? 'checked' : '' }}
                                           class="text-indigo-600">
                                    <span class="ml-2 text-sm">88603 Technique des systèmes</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="orientation" value="88614 Informaticienne d'entreprise CFC"
                                           {{ old('orientation') == '88614 Informaticienne d\'entreprise CFC' ? 'checked' : '' }}
                                           class="text-indigo-600">
                                    <span class="ml-2 text-sm">88614 Informaticienne d'entreprise CFC</span>
                                </label>
                            </div>
                        </div>

                        <!-- Chef de projet -->
                        <div class="border-t pt-4 mt-4">
                            <h4 class="font-semibold text-gray-800 mb-3">Chef de projet</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nom *</label>
                                    <input type="text" name="chef_projet_nom" required
                                           value="{{ old('chef_projet_nom') }}"
                                           placeholder="Martin"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Prénom *</label>
                                    <input type="text" name="chef_projet_prenom" required
                                           value="{{ old('chef_projet_prenom') }}"
                                           placeholder="Sophie"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                                    <input type="email" name="chef_projet_email" required
                                           value="{{ old('chef_projet_email') }}"
                                           placeholder="sophie.martin@example.com"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone *</label>
                                    <input type="text"
                                           name="chef_projet_telephone"
                                           id="chef_projet_telephone"
                                           required
                                           value="{{ old('chef_projet_telephone', '+41 ') }}"
                                           placeholder="+41 79 123 45 67"
                                           maxlength="16"
                                           oninput="window.formatSwissPhone(this)"
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
                                    <input type="text" name="expert1_nom" required
                                           value="{{ old('expert1_nom') }}"
                                           placeholder="Durand"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Prénom *</label>
                                    <input type="text" name="expert1_prenom" required
                                           value="{{ old('expert1_prenom') }}"
                                           placeholder="Pierre"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                                    <input type="email" name="expert1_email" required
                                           value="{{ old('expert1_email') }}"
                                           placeholder="pierre.durand@example.com"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone *</label>
                                    <input type="tel" name="expert1_telephone" required
                                           value="{{ old('expert1_telephone', '+41 ') }}"
                                           pattern="\+41\s[0-9]{2}\s[0-9]{3}\s[0-9]{2}\s[0-9]{2}"
                                           placeholder="+41 21 123 45 67"
                                           title="Format suisse : +41 XX XXX XX XX"
                                           maxlength="16"
                                           oninput="formatSwissPhone(this)"
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
                                    <input type="text" name="expert2_nom" required
                                           value="{{ old('expert2_nom') }}"
                                           placeholder="Blanc"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Prénom *</label>
                                    <input type="text" name="expert2_prenom" required
                                           value="{{ old('expert2_prenom') }}"
                                           placeholder="Marie"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                                    <input type="email" name="expert2_email" required
                                           value="{{ old('expert2_email') }}"
                                           placeholder="marie.blanc@example.com"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone *</label>
                                    <input type="tel" name="expert2_telephone" required
                                           value="{{ old('expert2_telephone', '+41 ') }}"
                                           pattern="\+41\s[0-9]{2}\s[0-9]{3}\s[0-9]{2}\s[0-9]{2}"
                                           placeholder="+41 21 123 45 67"
                                           title="Format suisse : +41 XX XXX XX XX"
                                           maxlength="16"
                                           oninput="formatSwissPhone(this)"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>
                        </div>

                        <!-- Période et horaire -->
                        <div class="border-t pt-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4" x-data="{
                                totalHours: {{ old('nombre_heures', '90') }}
                            }">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Période de réalisation *
                                    </label>
                                    <div class="grid grid-cols-2 gap-2">
                                        <div>
                                            <label class="text-xs text-gray-500">Du</label>
                                            <input type="date" name="date_debut" required
                                                   value="{{ old('date_debut', $prefillData['date_debut'] ?? '') }}"
                                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                        </div>
                                        <div>
                                            <label class="text-xs text-gray-500">Au</label>
                                            <input type="date" name="date_fin" required
                                                   value="{{ old('date_fin', $prefillData['date_fin'] ?? '') }}"
                                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Horaire de travail *
                                    </label>
                                    <div class="space-y-1">
                                        <div>
                                            <label class="text-xs text-gray-500">Matin</label>
                                            <div class="flex gap-1 items-center">
                                                <input type="time" name="heure_matin_debut" required
                                                       value="{{ old('heure_matin_debut', $prefillData['heure_matin_debut'] ?? '08:30') }}"
                                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                                <span class="text-xs">–</span>
                                                <input type="time" name="heure_matin_fin" required
                                                       value="{{ old('heure_matin_fin', $prefillData['heure_matin_fin'] ?? '12:30') }}"
                                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="text-xs text-gray-500">Après-midi</label>
                                            <div class="flex gap-1 items-center">
                                                <input type="time" name="heure_aprem_debut" required
                                                       value="{{ old('heure_aprem_debut', $prefillData['heure_aprem_debut'] ?? '13:30') }}"
                                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                                <span class="text-xs">–</span>
                                                <input type="time" name="heure_aprem_fin" required
                                                       value="{{ old('heure_aprem_fin', $prefillData['heure_aprem_fin'] ?? '17:30') }}"
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
                            total_heures: '{{ old('nombre_heures', '90') }}',
                            planning_analyse: '{{ old('planning_analyse', '15%') }}',
                            planning_implementation: '{{ old('planning_implementation', '50%') }}',
                            planning_tests: '{{ old('planning_tests', '20%') }}',
                            planning_documentation: '{{ old('planning_documentation', '15%') }}'
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

                <!-- Section 2: PROCÉDURE (Markdown) -->
                <div class="bg-white shadow-sm rounded-lg">
                    <div class="p-6 border-b border-gray-200 bg-indigo-50">
                        <h3 class="text-lg font-bold text-indigo-900">2. PROCÉDURE</h3>
                    </div>
                    <div class="p-6">
                        <x-markdown-editor
                            name="procedure"
                            :value="old('procedure', 'Le candidat réalise un travail personnel sur la base d\'un cahier des charges reçu le 1er jour.

                            Le cahier des charges est approuvé par les deux experts. Il est en outre présenté, commenté et discuté avec le candidat. Par sa signature, le candidat accepte le travail proposé.

                            Le candidat a connaissance de la feuille d\'évaluation avant de débuter le travail.

                            Le candidat est entièrement responsable de la sécurité de ses données.

                            En cas de problèmes graves, le candidat avertit au plus vite les deux experts et son CdP.

                            Le candidat a la possibilité d\'obtenir de l\'aide, mais doit le mentionner dans son dossier.

                            A la fin du délai imparti pour la réalisation du TPI, le candidat doit transmettre par courrier électronique le dossier de projet aux deux experts et au chef de projet.')"
                            label="Description de la procédure"
                            placeholder="Points de la procédure..."
                            help="Utilisez Markdown pour formater votre texte"
                            :rows="12"
                        />
                    </div>
                </div>

                <!-- Section 3: TITRE -->
                <div class="bg-white shadow-sm rounded-lg">
                    <div class="p-6 border-b border-gray-200 bg-indigo-50">
                        <h3 class="text-lg font-bold text-indigo-900">3. TITRE</h3>
                    </div>
                    <div class="p-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Titre du projet *
                        </label>
                        <input type="text" name="titre_projet" required
                               value="{{ old('titre_projet') }}"
                               placeholder="Ex: Skoob - Logiciel pour l'exploitation des librairies"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>

                <!-- Section 4: MATÉRIEL ET LOGICIEL (Markdown) -->
                <div class="bg-white shadow-sm rounded-lg">
                    <div class="p-6 border-b border-gray-200 bg-indigo-50">
                        <h3 class="text-lg font-bold text-indigo-900">4. MATÉRIEL ET LOGICIEL À DISPOSITION</h3>
                    </div>
                    <div class="p-6">
                        <x-markdown-editor
                            name="materiel_logiciel"
                            :value="old('materiel_logiciel', '')"
                            label="Matériel et logiciels"
                            placeholder="- 1 PC en configuration standard
                        - Environnement de développement Visual Studio
                        - 1 lecteur code-barres USB"
                            help="Utilisez des listes Markdown pour organiser le matériel"
                            :rows="6"
                        />
                    </div>
                </div>

                <!-- Section 5: PRÉREQUIS (Markdown) -->
                <div class="bg-white shadow-sm rounded-lg">
                    <div class="p-6 border-b border-gray-200 bg-indigo-50">
                        <h3 class="text-lg font-bold text-indigo-900">5. PRÉREQUIS</h3>
                    </div>
                    <div class="p-6">
                        <x-markdown-editor
                            name="prerequis"
                            :value="old('prerequis', '')"
                            label="Prérequis"
                            placeholder="- Connaissances du développement orienté objet
                            - Connaissance de C# et du framework .NET"
                            help="Utilisez des listes Markdown pour organiser les prérequis"
                            :rows="4"
                        />
                    </div>
                </div>

                <!-- Section 6: DESCRIPTIF DU PROJET (Markdown) -->
                <div class="bg-white shadow-sm rounded-lg">
                    <div class="p-6 border-b border-gray-200 bg-indigo-50">
                        <h3 class="text-lg font-bold text-indigo-900">6. DESCRIPTIF DU PROJET</h3>
                    </div>
                    <div class="p-6">
                        <x-markdown-editor
                            name="descriptif_projet"
                            :value="old('descriptif_projet', '')"
                            label="Description complète du projet"
                            placeholder="Le projet consiste à réaliser une application..."
                            help="Utilisez Markdown pour structurer et formater votre texte"
                            :rows="15"
                            required
                        />
                    </div>
                </div>

                <!-- Section 7: LIVRABLES (Markdown) -->
                <div class="bg-white shadow-sm rounded-lg">
                    <div class="p-6 border-b border-gray-200 bg-indigo-50">
                        <h3 class="text-lg font-bold text-indigo-900">7. LIVRABLES</h3>
                    </div>
                    <div class="p-6">
                        <x-markdown-editor
                            name="livrables"
                            :value="old('livrables', '')"
                            label="Livrables attendus"
                            placeholder="- Rapport de projet
                            - Journal de travail
                            - Planification initiale
                            - Code source complet
                            - Manuel utilisateur"
                            help="Utilisez des listes Markdown pour organiser les livrables"
                            :rows="6"
                        />
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="flex justify-end gap-4">
                    <a href="{{ route('forms.index') }}"
                       class="px-6 py-3 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition">
                        Annuler
                    </a>
                    <button type="submit"
                            :disabled="submitting"
                            :class="submitting ? 'opacity-50 cursor-not-allowed' : 'hover:bg-green-700'"
                            class="px-6 py-3 bg-green-600 text-white rounded-md transition font-medium shadow-lg">
                        <span x-show="!submitting">Créer le cahier des charges</span>
                        <span x-show="submitting" x-cloak>Création en cours...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/phone-formatter.js') }}"></script>
</x-app-layout>
