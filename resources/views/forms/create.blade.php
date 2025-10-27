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
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Prénom du candidat *
                                </label>
                                <input type="text" name="candidat_prenom" required
                                       value="{{ old('candidat_prenom') }}"
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
                                    <input type="radio" name="orientation" value="88601" class="text-indigo-600">
                                    <span class="ml-2 text-sm">88601 Développement d'applications</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="orientation" value="88602" class="text-indigo-600">
                                    <span class="ml-2 text-sm">88602 Informatique d'entreprise</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="orientation" value="88603" class="text-indigo-600">
                                    <span class="ml-2 text-sm">88603 Technique des systèmes</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="orientation" value="88614" class="text-indigo-600">
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
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Prénom *</label>
                                    <input type="text" name="chef_projet_prenom" required
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                                    <input type="email" name="chef_projet_email" required
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone *</label>
                                    <input type="tel" name="chef_projet_telephone" required
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
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Prénom *</label>
                                    <input type="text" name="expert1_prenom" required
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                                    <input type="email" name="expert1_email" required
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone *</label>
                                    <input type="tel" name="expert1_telephone" required
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
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Prénom *</label>
                                    <input type="text" name="expert2_prenom" required
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                                    <input type="email" name="expert2_email" required
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone *</label>
                                    <input type="tel" name="expert2_telephone" required
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>
                        </div>

                        <div class="border-t pt-4">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Période de réalisation *
                                    </label>
                                    <input type="text" name="periode_realisation" required
                                           placeholder="Ex: Du 3 au 26 mars 2025"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Horaire de travail *
                                    </label>
                                    <input type="text" name="horaire_travail" required
                                           placeholder="Ex: 8h-12h, 13h-17h"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Nombre d'heures *
                                    </label>
                                    <input type="text" name="nombre_heures" required
                                           placeholder="Ex: 120 heures"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>
                        </div>

                        <div class="border-t pt-4">
                            <h4 class="font-semibold text-gray-800 mb-3">Planning (en H ou %)</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Analyse</label>
                                    <input type="text" name="planning_analyse" placeholder="Ex: 20H ou 15%"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Implémentation</label>
                                    <input type="text" name="planning_implementation" placeholder="Ex: 60H ou 50%"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tests</label>
                                    <input type="text" name="planning_tests" placeholder="Ex: 20H ou 15%"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Documentations</label>
                                    <input type="text" name="planning_documentation" placeholder="Ex: 20H ou 20%"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>
                        </div>
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

                <div class="bg-white shadow-sm rounded-lg">
                    <div class="p-6 border-b border-gray-200 bg-indigo-50">
                        <h3 class="text-lg font-bold text-indigo-900">8. POINTS TECHNIQUES ÉVALUÉS</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <p class="text-sm text-gray-600 mb-4">
                            Définissez les 7 points techniques spécifiques qui seront évalués (Points A14 à A20)
                        </p>
                        @for($i = 1; $i <= 7; $i++)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Point technique {{ $i }}
                                </label>
                                <input type="text" name="point_technique_{{ $i }}"
                                       placeholder="Ex: Communication avec le lecteur code-barres"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        @endfor
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
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Nom du champ</label>
                                        <input type="text" :name="'fields[' + index + '][name]'" x-model="field.name" required
                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Label</label>
                                        <input type="text" :name="'fields[' + index + '][label]'" x-model="field.label" required
                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                    </div>
                                    <div class="col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Valeur</label>
                                        <textarea :name="'fields[' + index + '][value]'" x-model="field.value" rows="3"
                                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"></textarea>
                                    </div>
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
        function cdcFormBuilder() {
            const prefilledFields = @json($prefilledFields ?? []);

            return {
                fields: prefilledFields.length > 0
                    ? prefilledFields.map((field, index) => ({
                        ...field,
                        tempId: Date.now() + index,
                        value: field.value || ''
                    }))
                    : [],

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
