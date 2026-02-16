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
                                                <span class="text-xs">—</span>
                                                <input type="time" name="heure_matin_fin" required value="{{ $getValue('heure_matin_fin', $prefillData['heure_matin_fin'] ?? '12:30') }}"
                                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="text-xs text-gray-500">Après-midi</label>
                                            <div class="flex gap-1 items-center">
                                                <input type="time" name="heure_aprem_debut" required value="{{ $getValue('heure_aprem_debut', $prefillData['heure_aprem_debut'] ?? '13:30') }}"
                                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                                <span class="text-xs">—</span>
                                                <input type="time" name="heure_aprem_fin" required value="{{ $getValue('heure_aprem_fin', $prefillData['heure_aprem_fin'] ?? '17:30') }}"
                                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <input type="hidden" name="nombre_heures" x-model="totalHours" value="90">
                            </div>
                        </div>

                        <!-- Planning avec composants réutilisables -->
                        <div class="border-t pt-4" x-data="planningCalculatorEdit()">
                            <h4 class="font-semibold text-gray-800 mb-3 flex items-center justify-between">
                                <span>Planning (total : <span x-text="totalHeures + 'h'"></span>)</span>
                                <div class="flex gap-2">
                                    <button type="button" @click="mode = 'heures'"
                                            :class="mode === 'heures' ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700'"
                                            class="px-3 py-1 rounded-md text-xs font-medium transition">Heures</button>
                                    <button type="button" @click="mode = 'pourcentage'"
                                            :class="mode === 'pourcentage' ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700'"
                                            class="px-3 py-1 rounded-md text-xs font-medium transition">%</button>
                                </div>
                            </h4>

                            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                                <x-planning-slider model="analyse" label="Analyse" color="indigo" name="planning_analyse" />
                                <x-planning-slider model="implementation" label="Implémentation" color="green" name="planning_implementation" />
                                <x-planning-slider model="tests" label="Tests" color="orange" name="planning_tests" />
                                <x-planning-slider model="documentation" label="Documentation" color="purple" name="planning_documentation" />
                            </div>

                            <div class="mt-6 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border border-indigo-200">
                                <div class="flex justify-between items-center">
                                    <span class="font-semibold text-gray-800">Total planifié :</span>
                                    <span class="text-lg font-bold"
                                          :class="total > (mode === 'heures' ? totalHeures : 100) ? 'text-red-600' : 'text-green-600'"
                                          x-text="total + (mode === 'heures' ? 'h' : '%')"></span>
                                </div>
                                <div class="mt-2 text-xs text-gray-600">
                                    <span x-show="mode === 'pourcentage' && total !== 100" class="text-orange-600">⚠️ Le total devrait être 100%</span>
                                    <span x-show="mode === 'heures' && total > totalHeures" class="text-red-600">⚠️ Le total dépasse le nombre d'heures disponibles</span>
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
    <script src="{{ asset('js/form-builder.js') }}"></script>
    <script src="{{ asset('js/planning-calculator.js') }}"></script>
</x-app-layout>
