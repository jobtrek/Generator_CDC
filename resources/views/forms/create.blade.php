<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('forms.index') }}" class="mr-4 text-gray-600 hover:text-gray-900 transition">
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
            {{-- Alertes de session --}}
            @if(session('info'))
                <div x-data="{ show: true }" x-show="show" class="mb-4 p-4 bg-blue-100 border-l-4 border-blue-500 text-blue-700 rounded flex justify-between">
                    <span>{{ session('info') }}</span>
                    <button @click="show = false">✕</button>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded">
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded">
                    <p class="font-bold mb-2">Erreurs de validation :</p>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li class="text-sm">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('forms.store') }}" x-data="cdcFormBuilder()" class="space-y-6">
                @csrf

                {{-- SECTION 1 : INFORMATIONS GÉNÉRALES --}}
                <div class="bg-white shadow-sm rounded-lg">
                    <div class="p-6 border-b border-gray-200 bg-indigo-50">
                        <h3 class="text-lg font-bold text-indigo-900">1. INFORMATIONS GÉNÉRALES</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nom du candidat *</label>
                                <input type="text" name="candidat_nom" required value="{{ old('candidat_nom', $prefillData['candidat_nom'] ?? '') }}"
                                       placeholder="Dupont"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Prénom du candidat *</label>
                                <input type="text" name="candidat_prenom" required value="{{ old('candidat_prenom', $prefillData['candidat_prenom'] ?? '') }}"
                                       placeholder="Jean"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Lieu de travail *</label>
                            <input type="text" name="lieu_travail" required value="{{ old('lieu_travail', $prefillData['lieu_travail'] ?? '') }}"
                                   placeholder="Ex: ETML, Lausanne"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Orientation *</label>
                            <div class="space-y-2">
                                @php $orient = old('orientation', $prefillData['orientation'] ?? ''); @endphp
                                <label class="flex items-center">
                                    <input type="radio" name="orientation" value="88601 Développement d'applications"
                                           {{ $orient == '88601 Développement d\'applications' ? 'checked' : '' }}
                                           class="text-indigo-600">
                                    <span class="ml-2 text-sm">88601 Développement d'applications</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="orientation" value="88602 Informatique d'entreprise"
                                           {{ $orient == '88602 Informatique d\'entreprise' ? 'checked' : '' }}
                                           class="text-indigo-600">
                                    <span class="ml-2 text-sm">88602 Informatique d'entreprise</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="orientation" value="88603 Technique des systèmes"
                                           {{ $orient == '88603 Technique des systèmes' ? 'checked' : '' }}
                                           class="text-indigo-600">
                                    <span class="ml-2 text-sm">88603 Technique des systèmes</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="orientation" value="88614 Informaticienne d'entreprise CFC"
                                           {{ $orient == '88614 Informaticienne d\'entreprise CFC' ? 'checked' : '' }}
                                           class="text-indigo-600">
                                    <span class="ml-2 text-sm">88614 Informaticienne d'entreprise CFC</span>
                                </label>
                            </div>
                        </div>

                        {{-- CHEF DE PROJET --}}
                        <div class="border-t pt-4 mt-4">
                            <h4 class="font-semibold text-gray-800 mb-3">Chef de projet</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nom *</label>
                                    <input type="text" name="chef_projet_nom" required value="{{ old('chef_projet_nom', $prefillData['chef_projet_nom'] ?? '') }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Prénom *</label>
                                    <input type="text" name="chef_projet_prenom" required value="{{ old('chef_projet_prenom', $prefillData['chef_projet_prenom'] ?? '') }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                                    <input type="email" name="chef_projet_email" required value="{{ old('chef_projet_email', $prefillData['chef_projet_email'] ?? '') }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone *</label>
                                    <input type="tel" name="chef_projet_telephone" required
                                           value="{{ old('chef_projet_telephone', $prefillData['chef_projet_telephone'] ?? '') }}"
                                           @input="formatSwissPhone($event)"
                                           maxlength="16"
                                           placeholder="+41 79 123 45 67"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>
                        </div>

                        {{-- EXPERT 1 --}}
                        <div class="border-t pt-4">
                            <h4 class="font-semibold text-gray-800 mb-3">Expert 1</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nom *</label>
                                    <input type="text" name="expert1_nom" required value="{{ old('expert1_nom', $prefillData['expert1_nom'] ?? '') }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Prénom *</label>
                                    <input type="text" name="expert1_prenom" required value="{{ old('expert1_prenom', $prefillData['expert1_prenom'] ?? '') }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                                    <input type="email" name="expert1_email" required value="{{ old('expert1_email', $prefillData['expert1_email'] ?? '') }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone *</label>
                                    <input type="tel" name="expert1_telephone" required
                                           value="{{ old('expert1_telephone', $prefillData['expert1_telephone'] ?? '') }}"
                                           @input="formatSwissPhone($event)"
                                           maxlength="16"
                                           placeholder="+41 79 123 45 67"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>
                        </div>

                        {{-- EXPERT 2 --}}
                        <div class="border-t pt-4">
                            <h4 class="font-semibold text-gray-800 mb-3">Expert 2</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nom *</label>
                                    <input type="text" name="expert2_nom" required value="{{ old('expert2_nom', $prefillData['expert2_nom'] ?? '') }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Prénom *</label>
                                    <input type="text" name="expert2_prenom" required value="{{ old('expert2_prenom', $prefillData['expert2_prenom'] ?? '') }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                                    <input type="email" name="expert2_email" required value="{{ old('expert2_email', $prefillData['expert2_email'] ?? '') }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone *</label>
                                    <input type="tel" name="expert2_telephone" required
                                           value="{{ old('expert2_telephone', $prefillData['expert2_telephone'] ?? '') }}"
                                           @input="formatSwissPhone($event)"
                                           maxlength="16"
                                           placeholder="+41 79 123 45 67"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>
                        </div>

                        {{-- Horaires journaliers --}}
                        <div class="border-t pt-4 mt-4">
                            <h4 class="font-semibold text-gray-800 mb-3">Horaires de travail</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-xs text-gray-500 uppercase">Matin</label>
                                    <div class="grid grid-cols-2 gap-2">
                                        <input type="time" name="heure_matin_debut" required
                                               value="{{ old('heure_matin_debut', '08:00') }}"
                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                        <input type="time" name="heure_matin_fin" required
                                               value="{{ old('heure_matin_fin', '12:00') }}"
                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                    </div>
                                </div>
                                <div>
                                    <label class="text-xs text-gray-500 uppercase">Après-midi</label>
                                    <div class="grid grid-cols-2 gap-2">
                                        <input type="time" name="heure_aprem_debut" required
                                               value="{{ old('heure_aprem_debut', '13:00') }}"
                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                        <input type="time" name="heure_aprem_fin" required
                                               value="{{ old('heure_aprem_fin', '17:00') }}"
                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Période et Budget d'heures --}}
                        <div class="border-t pt-4 mt-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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

                                <input type="hidden" name="nombre_heures" id="totalHoursInput" value="90">
                            </div>
                        </div>

                        {{-- CALCULATEUR DE PLANNING DYNAMIQUE --}}
                        <div class="border-t pt-6 mt-6 bg-gray-50 p-4 rounded-lg border border-gray-200"
                             x-data="planningCalculator()"
                             @update-total.window="updateRef($event.detail.value)">

                            <div class="flex justify-between items-center mb-6">
                                <div>
                                    <h4 class="font-semibold text-gray-800 mb-2">Planning de travail détaillé</h4>
                                    <div class="flex items-center gap-2">
                                        <div class="h-3 w-3 rounded-full" :class="isOver ? 'bg-red-500' : 'bg-green-500'"></div>
                                        <span class="text-sm font-medium" :class="isOver ? 'text-red-700' : 'text-green-700'">
                                            Total planifié : <span x-text="currentSum"></span> <span x-text="unit"></span>
                                            / <span x-text="mode === 'heures' ? totalRef + 'h' : '100%'"></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="inline-flex bg-white rounded-lg p-1 border border-gray-300 shadow-sm">
                                    <button type="button" @click="setMode('heures')"
                                            :class="mode === 'heures' ? 'bg-indigo-600 text-white shadow' : 'text-gray-600 hover:bg-gray-50'"
                                            class="px-4 py-1.5 text-xs font-medium rounded-md transition">Heures</button>
                                    <button type="button" @click="setMode('pourcentage')"
                                            :class="mode === 'pourcentage' ? 'bg-indigo-600 text-white shadow' : 'text-gray-600 hover:bg-gray-50'"
                                            class="px-4 py-1.5 text-xs font-medium rounded-md transition">Pourcentage</button>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                                <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                                    <label class="block text-xs font-medium text-gray-600 uppercase mb-2">Analyse</label>
                                    <input type="range" x-model.number="analyse" :max="mode === 'heures' ? totalRef : 100" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-indigo-600">
                                    <div class="flex items-center justify-between mt-3">
                                        <input type="number" x-model.number="analyse" class="w-20 text-center font-bold text-indigo-600 border border-gray-300 rounded text-sm">
                                        <span class="text-xs font-medium text-gray-500" x-text="unit"></span>
                                    </div>
                                    <input type="hidden" name="planning_analyse" :value="analyse + (mode === 'heures' ? 'H' : '%')">
                                </div>

                                <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                                    <label class="block text-xs font-medium text-gray-600 uppercase mb-2">Implémentation</label>
                                    <input type="range" x-model.number="implementation" :max="mode === 'heures' ? totalRef : 100" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-green-600">
                                    <div class="flex items-center justify-between mt-3">
                                        <input type="number" x-model.number="implementation" class="w-20 text-center font-bold text-green-600 border border-gray-300 rounded text-sm">
                                        <span class="text-xs font-medium text-gray-500" x-text="unit"></span>
                                    </div>
                                    <input type="hidden" name="planning_implementation" :value="implementation + (mode === 'heures' ? 'H' : '%')">
                                </div>

                                <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                                    <label class="block text-xs font-medium text-gray-600 uppercase mb-2">Tests</label>
                                    <input type="range" x-model.number="tests" :max="mode === 'heures' ? totalRef : 100" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-orange-600">
                                    <div class="flex items-center justify-between mt-3">
                                        <input type="number" x-model.number="tests" class="w-20 text-center font-bold text-orange-600 border border-gray-300 rounded text-sm">
                                        <span class="text-xs font-medium text-gray-500" x-text="unit"></span>
                                    </div>
                                    <input type="hidden" name="planning_tests" :value="tests + (mode === 'heures' ? 'H' : '%')">
                                </div>

                                <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                                    <label class="block text-xs font-medium text-gray-600 uppercase mb-2">Documentation</label>
                                    <input type="range" x-model.number="documentation" :max="mode === 'heures' ? totalRef : 100" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-purple-600">
                                    <div class="flex items-center justify-between mt-3">
                                        <input type="number" x-model.number="documentation" class="w-20 text-center font-bold text-purple-600 border border-gray-300 rounded text-sm">
                                        <span class="text-xs font-medium text-gray-500" x-text="unit"></span>
                                    </div>
                                    <input type="hidden" name="planning_documentation" :value="documentation + (mode === 'heures' ? 'H' : '%')">
                                </div>
                            </div>

                            <template x-if="isOver">
                                <div class="mt-4 p-3 bg-red-100 text-red-700 text-sm rounded border border-red-200 font-medium">
                                    ⚠️ Attention : Le total dépasse <span x-text="mode === 'heures' ? totalRef + 'h' : '100%'"></span>.
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- SECTION 2 : PROCÉDURE --}}
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
                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ old('procedure', 'Le cahier des charges est approuvé par les deux experts. Il est en outre présenté, commenté et discuté avec le candidat. Par sa signature, le candidat accepte le travail proposé.
Le candidat a connaissance de la feuille d\'évaluation avant de débuter le travail.
Le candidat est entièrement responsable de la sécurité de ses données.
En cas de problèmes graves, le candidat avertit au plus vite les deux experts et son CdP.
Le candidat a la possibilité d\'obtenir de l\'aide, mais doit le mentionner dans son dossier.
A la fin du délai imparti pour la réalisation du TPI, le candidat doit transmettre par courrier électronique le dossier de projet aux deux experts et au chef de projet. En parallèle, une copie papier du rapport doit être fournie sans délai en trois exemplaires (L\'un des deux experts peut demander à ne recevoir que la version électronique du dossier). Cette dernière doit être en tout point identique à la version électronique.') }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">Séparez chaque point par une nouvelle ligne</p>
                    </div>
                </div>

                {{-- SECTION 3 : TITRE --}}
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

                {{-- SECTION 4 : MATÉRIEL ET LOGICIEL --}}
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

                {{-- SECTION 5 : PRÉREQUIS --}}
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

                {{-- SECTION 6 : DESCRIPTIF DU PROJET --}}
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
                            required
                        />
                    </div>
                </div>

                {{-- SECTION 7 : LIVRABLES --}}
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

                {{-- CHAMPS PERSONNALISÉS --}}
                <div class="bg-white shadow-sm rounded-lg" x-show="fields.length > 0" style="display: none;">
                    <div class="p-6 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-lg font-bold text-gray-900">Champs personnalisés supplémentaires</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <template x-for="(field, index) in fields" :key="field.tempId">
                            <div class="border rounded-lg p-4 bg-gray-50 relative">
                                <button type="button" @click="removeField(index)"
                                        class="absolute top-2 right-2 text-red-600 hover:text-red-800 transition">
                                    ✕
                                </button>

                                <div class="grid grid-cols-2 gap-4 pr-12">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Clé</label>
                                        <input type="text" x-model="field.key" disabled class="w-full rounded-md border-gray-300 bg-gray-100 text-gray-600">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Valeur</label>
                                        <input type="text" x-model="field.value" placeholder="Valeur du champ" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- BOUTONS D'ACTIONS --}}
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
        function formatSwissPhone(e) {
            let val = e.target.value.replace(/\D/g, '');

            if (val.length > 0) {
                if (val.startsWith('0')) {
                    val = '41' + val.substring(1);
                }
                else if (!val.startsWith('41')) {
                    val = '41' + val;
                }
            }

            val = val.substring(0, 11);

            let newVal = '';
            if (val.length > 0) newVal += '+' + val.substring(0, 2);
            if (val.length > 2) newVal += ' ' + val.substring(2, 4);
            if (val.length > 4) newVal += ' ' + val.substring(4, 7);
            if (val.length > 7) newVal += ' ' + val.substring(7, 9);
            if (val.length > 9) newVal += ' ' + val.substring(9, 11);

            e.target.value = newVal;
        }

        function planningCalculator() {
            return {
                mode: 'heures',
                totalRef: 90,
                analyse: 15,
                implementation: 45,
                tests: 20,
                documentation: 10,

                init() {
                    const input = document.getElementById('totalHoursInput');
                    if(input) this.totalRef = parseInt(input.value) || 90;
                },

                updateRef(val) {
                    this.totalRef = parseInt(val) || 90;
                },

                get currentSum() {
                    return (this.analyse||0) + (this.implementation||0) + (this.tests||0) + (this.documentation||0);
                },

                get unit() {
                    return this.mode === 'heures' ? 'h' : '%';
                },

                get isOver() {
                    return this.mode === 'heures' ? (this.currentSum > this.totalRef) : (this.currentSum > 100);
                },

                setMode(newMode) {
                    if (this.mode === newMode) return;

                    const input = document.getElementById('totalHoursInput');
                    if(input) this.totalRef = parseInt(input.value) || 90;

                    const targetTotal = (newMode === 'pourcentage') ? 100 : this.totalRef;
                    const sourceTotal = (newMode === 'pourcentage') ? this.totalRef : 100;

                    // Produit en croix
                    let a = (this.analyse / sourceTotal) * targetTotal;
                    let i = (this.implementation / sourceTotal) * targetTotal;
                    let t = (this.tests / sourceTotal) * targetTotal;
                    let d = (this.documentation / sourceTotal) * targetTotal;

                    // Arrondi simple
                    this.analyse = Math.round(a);
                    this.implementation = Math.round(i);
                    this.tests = Math.round(t);
                    this.documentation = Math.round(d);

                    // Correction des erreurs d'arrondi (ex: 101%)
                    this.fixRoundingError(targetTotal);

                    this.mode = newMode;
                },

                fixRoundingError(targetTotal) {
                    let sum = this.analyse + this.implementation + this.tests + this.documentation;
                    let diff = targetTotal - sum;

                    if (diff !== 0) {
                        // On ajoute/retire la différence à la plus grande valeur
                        let values = {
                            'analyse': this.analyse,
                            'implementation': this.implementation,
                            'tests': this.tests,
                            'documentation': this.documentation
                        };

                        let maxKey = Object.keys(values).reduce((a, b) => values[a] > values[b] ? a : b);
                        this[maxKey] += diff;
                    }
                }
            };
        }

        function cdcFormBuilder() {
            const prefilledFields = @json($prefilledFields ?? []);

            return {
                fields: prefilledFields.map(f => ({
                    tempId: Date.now() + Math.random(),
                    key: f.key,
                    value: f.value
                })),

                removeField(index) {
                    this.fields.splice(index, 1);
                }
            };
        }
    </script>
</x-app-layout>
