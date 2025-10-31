<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('forms.show', $form) }}" class="mr-4 text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Modifier le cahier des charges
            </h2>
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
                                   value="{{ old('name', $form->name) }}"
                                   placeholder="Ex: Cahier des charges TPI 2025"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                                Description
                            </label>
                            <textarea name="description" id="description" rows="2"
                                      placeholder="Description du formulaire..."
                                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $form->description) }}</textarea>
                        </div>

                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" value="1"
                                   {{ old('is_active', $form->is_active) ? 'checked' : '' }}
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
                                       value="{{ $getValue('candidat_nom') }}"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Prénom du candidat *
                                </label>
                                <input type="text" name="candidat_prenom" required
                                       value="{{ $getValue('candidat_prenom') }}"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Lieu de travail *
                            </label>
                            <input type="text" name="lieu_travail" required
                                   value="{{ $getValue('lieu_travail') }}"
                                   placeholder="Ex: ETML, Lausanne"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Orientation *
                            </label>
                            <div class="space-y-2">
                                @php
                                    $currentOrientation = $getValue('orientation');
                                @endphp
                                <label class="flex items-center">
                                    <input type="radio" name="orientation" value="88601"
                                           {{ $currentOrientation == '88601' ? 'checked' : '' }}
                                           class="text-indigo-600">
                                    <span class="ml-2 text-sm">88601 Développement d'applications</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="orientation" value="88602"
                                           {{ $currentOrientation == '88602' ? 'checked' : '' }}
                                           class="text-indigo-600">
                                    <span class="ml-2 text-sm">88602 Informatique d'entreprise</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="orientation" value="88603"
                                           {{ $currentOrientation == '88603' ? 'checked' : '' }}
                                           class="text-indigo-600">
                                    <span class="ml-2 text-sm">88603 Technique des systèmes</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="orientation" value="88614"
                                           {{ $currentOrientation == '88614' ? 'checked' : '' }}
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
                                           value="{{ $getValue('chef_projet_nom') }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Prénom *</label>
                                    <input type="text" name="chef_projet_prenom" required
                                           value="{{ $getValue('chef_projet_prenom') }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                                    <input type="email" name="chef_projet_email" required
                                           value="{{ $getValue('chef_projet_email') }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone *</label>
                                    <input type="tel" name="chef_projet_telephone" required
                                           value="{{ $getValue('chef_projet_telephone') }}"
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
                                           value="{{ $getValue('expert1_nom') }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Prénom *</label>
                                    <input type="text" name="expert1_prenom" required
                                           value="{{ $getValue('expert1_prenom') }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                                    <input type="email" name="expert1_email" required
                                           value="{{ $getValue('expert1_email') }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone *</label>
                                    <input type="tel" name="expert1_telephone" required
                                           value="{{ $getValue('expert1_telephone') }}"
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
                                           value="{{ $getValue('expert2_nom') }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Prénom *</label>
                                    <input type="text" name="expert2_prenom" required
                                           value="{{ $getValue('expert2_prenom') }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                                    <input type="email" name="expert2_email" required
                                           value="{{ $getValue('expert2_email') }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone *</label>
                                    <input type="tel" name="expert2_telephone" required
                                           value="{{ $getValue('expert2_telephone') }}"
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
                                           value="{{ $getValue('periode_realisation') }}"
                                           placeholder="Ex: Du 3 au 26 mars 2025"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Horaire de travail *
                                    </label>
                                    <input type="text" name="horaire_travail" required
                                           value="{{ $getValue('horaire_travail') }}"
                                           placeholder="Ex: 8h-12h, 13h-17h"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Nombre d'heures *
                                    </label>
                                    <input type="text" name="nombre_heures" required
                                           value="{{ $getValue('nombre_heures') }}"
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
                                    <input type="text" name="planning_analyse"
                                           value="{{ $getValue('planning_analyse') }}"
                                           placeholder="Ex: 20H ou 15%"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Implémentation</label>
                                    <input type="text" name="planning_implementation"
                                           value="{{ $getValue('planning_implementation') }}"
                                           placeholder="Ex: 60H ou 50%"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tests</label>
                                    <input type="text" name="planning_tests"
                                           value="{{ $getValue('planning_tests') }}"
                                           placeholder="Ex: 20H ou 15%"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Documentations</label>
                                    <input type="text" name="planning_documentation"
                                           value="{{ $getValue('planning_documentation') }}"
                                           placeholder="Ex: 20H ou 20%"
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
                               value="{{ $getValue('titre_projet') }}"
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
                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ $getValue('materiel_logiciel') }}</textarea>
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
                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ $getValue('prerequis') }}</textarea>
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
                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 font-mono text-sm">{{ $getValue('descriptif_projet') }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">Utilisez le formatage Markdown pour structurer votre texte</p>
                    </div>
                </div>

                <!-- Section 7: LIVRABLES -->
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
                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ $getValue('livrables') }}</textarea>
                    </div>
                </div>

                @php
                    $customFields = $form->fields->where('section', 'custom');
                @endphp

                @if($customFields->count() > 0)
                    <div class="bg-white shadow-sm rounded-lg">
                        <div class="p-6 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-lg font-bold text-gray-900">Champs personnalisés supplémentaires</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            @foreach($customFields as $index => $field)
                                <div class="border rounded-lg p-4 bg-gray-50 relative">
                                    <button type="button" @click="removeCustomField({{ $field->id }})"
                                            class="absolute top-2 right-2 p-2 text-red-600 hover:bg-red-100 rounded">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>

                                    <div class="grid grid-cols-2 gap-4 pr-12">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Nom du champ</label>
                                            <input type="text" name="fields[{{ $index }}][name]" value="{{ $field->name }}" required
                                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                            <input type="hidden" name="fields[{{ $index }}][id]" value="{{ $field->id }}">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Label</label>
                                            <input type="text" name="fields[{{ $index }}][label]" value="{{ $field->label }}" required
                                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                        </div>
                                        <div class="col-span-2">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Valeur</label>
                                            <textarea name="fields[{{ $index }}][value]" rows="3"
                                                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ $getValue($field->name) }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="bg-white shadow-sm rounded-lg" x-data="{ newFields: [] }">
                    <div class="p-6">
                        <button type="button" @click="newFields.push({ tempId: Date.now(), name: '', label: '', value: '' })"
                                class="w-full py-3 border-2 border-dashed border-gray-300 rounded-lg text-gray-600 hover:border-indigo-500 hover:text-indigo-600 transition">
                            + Ajouter un champ personnalisé
                        </button>

                        <div class="mt-4 space-y-4" x-show="newFields.length > 0">
                            <template x-for="(field, index) in newFields" :key="field.tempId">
                                <div class="border rounded-lg p-4 bg-gray-50 relative">
                                    <button type="button" @click="newFields.splice(index, 1)"
                                            class="absolute top-2 right-2 p-2 text-red-600 hover:bg-red-100 rounded">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                    <div class="grid grid-cols-2 gap-4 pr-12">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Nom du champ</label>
                                            <input type="text" :name="'new_fields[' + index + '][name]'" x-model="field.name" required
                                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Label</label>
                                            <input type="text" :name="'new_fields[' + index + '][label]'" x-model="field.label" required
                                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                        </div>
                                        <div class="col-span-2">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Valeur</label>
                                            <textarea :name="'new_fields[' + index + '][value]'" x-model="field.value" rows="3"
                                                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"></textarea>
                                        </div>
                                        <input type="hidden" :name="'new_fields[' + index + '][field_type_id]'" value="1">
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-4">
                    <a href="{{ route('forms.show', $form) }}"
                       class="px-6 py-3 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition">
                        Annuler
                    </a>
                    <button type="submit"
                            class="px-6 py-3 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition font-medium shadow-lg">
                        Mettre à jour le formulaire
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function cdcFormBuilder() {
            return {
                removeCustomField(fieldId) {
                    if (confirm('Êtes-vous sûr de vouloir supprimer ce champ ?')) {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'deleted_fields[]';
                        input.value = fieldId;
                        document.querySelector('form').appendChild(input);

                        event.target.closest('.border').style.display = 'none';
                    }
                }
            };
        }
    </script>
</x-app-layout>
