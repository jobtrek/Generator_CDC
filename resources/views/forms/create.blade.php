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

            {{-- Le cdcFormBuilder enveloppe tout le formulaire --}}
            <form method="POST" action="{{ route('forms.store') }}" x-data="cdcFormBuilder()" class="space-y-6">
                @csrf

                <div class="bg-white shadow-sm rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-indigo-700 flex items-center">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Informations du formulaire
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                Nom du formulaire *
                            </label>
                            <input type="text" name="name" id="name" required
                                   value="{{ old('name', $duplicateData['name'] ?? '') }}"
                                   placeholder="Ex: Cahier des charges TPI 2025"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                                Description
                            </label>
                            <textarea name="description" id="description" rows="2"
                                      placeholder="Description du formulaire..."
                                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $duplicateData['description'] ?? '') }}</textarea>
                        </div>

                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" value="1" checked
                                   class="rounded border-gray-300 text-indigo-600 shadow-sm">
                            <span class="ml-2 text-sm text-gray-600">Formulaire actif</span>
                        </label>
                    </div>
                </div>

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
                                    <input type="tel" name="chef_projet_telephone" required
                                           value="{{ old('chef_projet_telephone', '+41 ') }}"
                                           pattern="[\+]?[0-9\s\-\(\)]+"
                                           placeholder="+41 21 123 45 67"
                                           title="Veuillez entrer un numéro de téléphone valide (ex: +41 21 123 45 67)"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>
                        </div>

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
                                           pattern="[\+]?[0-9\s\-\(\)]+"
                                           placeholder="+41 21 123 45 67"
                                           title="Veuillez entrer un numéro de téléphone valide (ex: +41 21 123 45 67)"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>
                        </div>

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
                                           pattern="[\+]?[0-9\s\-\(\)]+"
                                           placeholder="+41 21 123 45 67"
                                           title="Veuillez entrer un numéro de téléphone valide (ex: +41 21 123 45 67)"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>
                        </div>
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

                        <div class="border-t pt-4" x-data="planningCalculatorEdit()">
                            <h4 class="font-semibold text-gray-800 mb-3 flex items-center justify-between">
                                <span>Planning (total : <span x-text="totalHeures + 'h'"></span>)</span>
                                <div class="flex gap-2">
                                    <button type="button"
                                            @click="mode = 'heures'"
                                            :class="mode === 'heures' ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700'"
                                            class="px-3 py-1 rounded-md text-xs font-medium transition">
                                        Heures
                                    </button>
                                    <button type="button"
                                            @click="mode = 'pourcentage'"
                                            :class="mode === 'pourcentage' ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700'"
                                            class="px-3 py-1 rounded-md text-xs font-medium transition">
                                        %
                                    </button>
                                </div>
                            </h4>

                            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Analyse
                                        <span class="text-xs text-indigo-600 font-semibold" x-text="'(' + analyse + (mode === 'heures' ? 'h' : '%') + ')'"></span>
                                    </label>
                                    <input type="range"
                                           x-model.number="analyse"
                                           :min="0"
                                           :max="mode === 'heures' ? totalHeures : 100"
                                           :step="mode === 'heures' ? 1 : 5"
                                           class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-indigo-600">
                                    <div class="flex justify-between items-center mt-2">
                                        <input type="number"
                                               x-model.number="analyse"
                                               :min="0"
                                               :max="mode === 'heures' ? totalHeures : 100"
                                               class="w-20 text-sm rounded border-gray-300">
                                        <span class="text-xs text-gray-500" x-text="mode === 'heures' ? 'heures' : '%'"></span>
                                    </div>
                                    <input type="hidden" name="planning_analyse" :value="formatValue(analyse)">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Implémentation
                                        <span class="text-xs text-green-600 font-semibold" x-text="'(' + implementation + (mode === 'heures' ? 'h' : '%') + ')'"></span>
                                    </label>
                                    <input type="range"
                                           x-model.number="implementation"
                                           :min="0"
                                           :max="mode === 'heures' ? totalHeures : 100"
                                           :step="mode === 'heures' ? 1 : 5"
                                           class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-green-600">
                                    <div class="flex justify-between items-center mt-2">
                                        <input type="number"
                                               x-model.number="implementation"
                                               :min="0"
                                               :max="mode === 'heures' ? totalHeures : 100"
                                               class="w-20 text-sm rounded border-gray-300">
                                        <span class="text-xs text-gray-500" x-text="mode === 'heures' ? 'heures' : '%'"></span>
                                    </div>
                                    <input type="hidden" name="planning_implementation" :value="formatValue(implementation)">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Tests
                                        <span class="text-xs text-orange-600 font-semibold" x-text="'(' + tests + (mode === 'heures' ? 'h' : '%') + ')'"></span>
                                    </label>
                                    <input type="range"
                                           x-model.number="tests"
                                           :min="0"
                                           :max="mode === 'heures' ? totalHeures : 100"
                                           :step="mode === 'heures' ? 1 : 5"
                                           class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-orange-600">
                                    <div class="flex justify-between items-center mt-2">
                                        <input type="number"
                                               x-model.number="tests"
                                               :min="0"
                                               :max="mode === 'heures' ? totalHeures : 100"
                                               class="w-20 text-sm rounded border-gray-300">
                                        <span class="text-xs text-gray-500" x-text="mode === 'heures' ? 'heures' : '%'"></span>
                                    </div>
                                    <input type="hidden" name="planning_tests" :value="formatValue(tests)">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Documentation
                                        <span class="text-xs text-purple-600 font-semibold" x-text="'(' + documentation + (mode === 'heures' ? 'h' : '%') + ')'"></span>
                                    </label>
                                    <input type="range"
                                           x-model.number="documentation"
                                           :min="0"
                                           :max="mode === 'heures' ? totalHeures : 100"
                                           :step="mode === 'heures' ? 1 : 5"
                                           class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-purple-600">
                                    <div class="flex justify-between items-center mt-2">
                                        <input type="number"
                                               x-model.number="documentation"
                                               :min="0"
                                               :max="mode === 'heures' ? totalHeures : 100"
                                               class="w-20 text-sm rounded border-gray-300">
                                        <span class="text-xs text-gray-500" x-text="mode === 'heures' ? 'heures' : '%'"></span>
                                    </div>
                                    <input type="hidden" name="planning_documentation" :value="formatValue(documentation)">
                                </div>
                            </div>

                            <div class="mt-6 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border border-indigo-200">
                                <div class="flex justify-between items-center">
                                    <span class="font-semibold text-gray-800">Total planifié :</span>
                                    <span class="text-lg font-bold"
                                          :class="total > (mode === 'heures' ? totalHeures : 100) ? 'text-red-600' : 'text-green-600'"
                                          x-text="total + (mode === 'heures' ? 'h' : '%')"></span>
                                </div>
                                <div class="mt-2 text-xs text-gray-600">
                                <span x-show="mode === 'pourcentage' && total !== 100" class="text-orange-600">
                                    ⚠️ Le total devrait être 100%
                                </span>
                                    <span x-show="mode === 'heures' && total > totalHeures" class="text-red-600">
                                    ⚠️ Le total dépasse le nombre d'heures disponibles (max <span x-text="totalHeures"></span>h)
                                </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow-sm rounded-lg">
                    <div class="p-6 border-b border-gray-200 bg-indigo-50">
                        <h3 class="text-lg font-bold text-indigo-900">2. PROCÉDURE</h3>
                    </div>
                    <div class="p-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Description de la procédure
                        </label>
                        <textarea name="procedure" rows="15"
                                  placeholder="Points de la procédure (un par ligne)"
                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ old('procedure', 'Le candidat réalise un travail personnel sur la base d\'un cahier des charges reçu le 1er jour.
                            Le cahier des charges est approuvé par les deux experts. Il est en outre présenté, commenté et discuté avec le candidat. Par sa signature, le candidat accepte le travail proposé.
                            Le candidat a connaissance de la feuille d\'évaluation avant de débuter le travail.
                            Le candidat est entièrement responsable de la sécurité de ses données.
                            En cas de problèmes graves, le candidat avertit au plus vite les deux experts et son CdP.
                            Le candidat a la possibilité d\'obtenir de l\'aide, mais doit le mentionner dans son dossier.
                            A la fin du délai imparti pour la réalisation du TPI, le candidat doit transmettre par courrier électronique le dossier de projet aux deux experts et au chef de projet. En parallèle, une copie papier du rapport doit être fournie sans délai en trois exemplaires (L\'un des deux experts peut demander à ne recevoir que la version électronique du dossier). Cette dernière doit être en tout point identique à la version électronique.') }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">Séparez chaque point par une nouvelle ligne</p>
                    </div>
                </div>

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

                <div class="bg-white shadow-sm rounded-lg">
                    <div class="p-6 border-b border-gray-200 bg-indigo-50">
                        <h3 class="text-lg font-bold text-indigo-900">4. MATÉRIEL ET LOGICIEL À DISPOSITION</h3>
                    </div>
                    <div class="p-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Matériel et logiciels (un par ligne)
                        </label>
                        <textarea name="materiel_logiciel" rows="6"
                                  placeholder="1 PC en configuration standard&#10;Environnement de développement Visual Studio&#10;1 lecteur code-barres USB"
                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('materiel_logiciel') }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">Séparez chaque élément par une nouvelle ligne</p>
                    </div>
                </div>

                <div class="bg-white shadow-sm rounded-lg">
                    <div class="p-6 border-b border-gray-200 bg-indigo-50">
                        <h3 class="text-lg font-bold text-indigo-900">5. PRÉREQUIS</h3>
                    </div>
                    <div class="p-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Prérequis (un par ligne)
                        </label>
                        <textarea name="prerequis" rows="4"
                                  placeholder="Connaissances du développement orienté objet&#10;Connaissance de C# et du framework .NET"
                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('prerequis') }}</textarea>
                    </div>
                </div>

                <div class="bg-white shadow-sm rounded-lg">
                    <div class="p-6 border-b border-gray-200 bg-indigo-50">
                        <h3 class="text-lg font-bold text-indigo-900">6. DESCRIPTIF DU PROJET</h3>
                    </div>
                    <div class="p-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Description complète du projet *
                        </label>
                        <div class="mb-2 p-3 bg-blue-50 border border-blue-200 rounded">
                            <p class="text-sm text-blue-800 font-medium mb-2">💡 Aide au formatage Markdown :</p>
                            <ul class="text-xs text-blue-700 space-y-1">
                                <li>• <code class="bg-blue-100 px-1 rounded">**texte**</code> pour <strong>gras</strong></li>
                                <li>• <code class="bg-blue-100 px-1 rounded">*texte*</code> pour <em>italique</em></li>
                                <li>• <code class="bg-blue-100 px-1 rounded">- item</code> pour liste à puces</li>
                                <li>• <code class="bg-blue-100 px-1 rounded">1. item</code> pour liste numérotée</li>
                                <li>• Laisser une ligne vide pour nouveau paragraphe</li>
                            </ul>
                        </div>
                        <textarea name="descriptif_projet" rows="12" required
                                  placeholder="Le projet consiste à réaliser une application clé en main pour la gestion du stock d'une petite librairie.

L'application s'adresse à des personnes qui n'ont pratiquement aucune notion en informatique.

**Cas d'utilisation:**
- Entrée en stock
  - Scénario 1: saisie du code ISBN
  - Scénario 2: saisie d'un nouvel ouvrage
- Sortie de stock
- Inventaire"
                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 font-mono text-sm">{{ old('descriptif_projet') }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">Utilisez le formatage Markdown pour structurer votre texte</p>
                    </div>
                </div>

                <div class="bg-white shadow-sm rounded-lg">
                    <div class="p-6 border-b border-gray-200 bg-indigo-50">
                        <h3 class="text-lg font-bold text-indigo-900">7. LIVRABLES</h3>
                    </div>
                    <div class="p-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Livrables attendus (un par ligne)
                        </label>
                        <textarea name="livrables" rows="6"
                                  placeholder="Rapport de projet&#10;Journal de travail&#10;Planification initiale&#10;Code source complet&#10;Manuel utilisateur"
                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('livrables') }}</textarea>
                    </div>
                </div>

                <div class="bg-white shadow-sm rounded-lg" x-show="fields.length > 0">
                    <div class="p-6 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-lg font-bold text-gray-900">Champs personnalisés supplémentaires</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <template x-for="(field, index) in fields" :key="field.tempId">
                            <div class="border rounded-lg p-4 bg-gray-50 relative">
                                <button type="button" @click="removeField(index)"
                                        class="absolute top-2 right-2 p-2 text-red-600 hover:bg-red-100 rounded">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>

                                <div class="grid grid-cols-2 gap-4 pr-12">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Nom du champ *</label>
                                        <input type="text" :name="'fields[' + index + '][name]'" x-model="field.name" required
                                               placeholder="nom_du_champ"
                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Label *</label>
                                        <input type="text" :name="'fields[' + index + '][label]'" x-model="field.label" required
                                               placeholder="Libellé du champ"
                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                    </div>
                                    <div class="col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Valeur</label>
                                        <textarea :name="'fields[' + index + '][value]'" x-model="field.value" rows="3"
                                                  placeholder="Contenu du champ personnalisé"
                                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"></textarea>
                                    </div>
                                    <input type="hidden" :name="'fields[' + index + '][field_type_id]'" value="1">
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="bg-white shadow-sm rounded-lg">
                    <div class="p-6">
                        <button type="button" @click="addField"
                                class="w-full py-3 border-2 border-dashed border-gray-300 rounded-lg text-gray-600 hover:border-indigo-500 hover:text-indigo-600 transition">
                            + Ajouter un champ personnalisé
                        </button>
                    </div>
                </div>

                <div class="flex justify-end gap-4">
                    <a href="{{ route('forms.index') }}"
                       class="px-6 py-3 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition">
                        Annuler
                    </a>
                    <button type="submit"
                            class="px-6 py-3 bg-green-600 text-white rounded-md hover:bg-green-700 transition font-medium shadow-lg">
                        Créer et générer le CDC
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function planningCalculatorEdit() {
            const parseOldValue = (oldStr, defaultVal) => {
                if (!oldStr || oldStr === '') return defaultVal;
                const cleaned = String(oldStr).replace(/[H%]/g, '').trim();
                const parsed = parseInt(cleaned);
                return isNaN(parsed) ? defaultVal : parsed;
            };

            const oldAnalyse = "{{ old('planning_analyse') }}";
            const defaultMode = oldAnalyse.includes('H') ? 'heures' : 'pourcentage';

            return {
                mode: defaultMode,
                analyse: parseOldValue("{{ old('planning_analyse') }}", 15),
                implementation: parseOldValue("{{ old('planning_implementation') }}", 50),
                tests: parseOldValue("{{ old('planning_tests') }}", 20),
                documentation: parseOldValue("{{ old('planning_documentation') }}", 15),

                get totalHeures() {
                    const input = document.querySelector('input[name="nombre_heures"]');
                    return parseInt(input?.value || 120);
                },

                get total() {
                    return parseInt(this.analyse || 0) +
                        parseInt(this.implementation || 0) +
                        parseInt(this.tests || 0) +
                        parseInt(this.documentation || 0);
                },

                formatValue(val) {
                    return val + (this.mode === 'heures' ? 'H' : '%');
                },

                init() {
                    this.$watch('mode', () => {
                        if (this.mode === 'heures') {
                            const max = this.totalHeures;
                            this.analyse = Math.min(this.analyse, max);
                            this.implementation = Math.min(this.implementation, max);
                            this.tests = Math.min(this.tests, max);
                            this.documentation = Math.min(this.documentation, max);
                        }
                    });
                }
            };
        }

        function cdcFormBuilder() {
            const prefilledFields = @json($prefilledFields ?? []);

            return {
                fields: prefilledFields.filter(field =>
                    !['candidat_nom', 'candidat_prenom', 'lieu_travail', 'orientation',
                        'chef_projet_nom', 'chef_projet_prenom', 'chef_projet_email', 'chef_projet_telephone',
                        'expert1_nom', 'expert1_prenom', 'expert1_email', 'expert1_telephone',
                        'expert2_nom', 'expert2_prenom', 'expert2_email', 'expert2_telephone',
                        'periode_realisation', 'horaire_travail', 'nombre_heures',
                        'planning_analyse', 'planning_implementation', 'planning_tests', 'planning_documentation',
                        'titre_projet', 'materiel_logiciel', 'prerequis', 'descriptif_projet', 'livrables',
                        'date_debut', 'date_fin', 'heure_debut', 'heure_fin'
                    ].includes(field.name)
                ).map((field, index) => ({
                    ...field,
                    tempId: Date.now() + index,
                    value: field.value || ''
                })),

                tempIdCounter: Date.now() + (prefilledFields.length || 0),

                addField() {
                    this.fields.push({
                        tempId: this.tempIdCounter++,
                        name: '',
                        label: '',
                        field_type_id: '1',
                        value: ''
                    });
                },

                removeField(index) {
                    if (confirm('Êtes-vous sûr de vouloir supprimer ce champ ?')) {
                        this.fields.splice(index, 1);
                    }
                }
            };
        }
    </script>
</x-app-layout>
