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
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            @if(session('info'))
                <div x-data="{ show: true }" x-show="show" x-transition
                     class="mb-4 p-4 bg-blue-100 border-l-4 border-blue-500 text-blue-700 rounded flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ session('info') }}</span>
                    </div>
                    <button @click="show = false" class="text-blue-700 hover:text-blue-900">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            @endif

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

            <form method="POST" action="{{ route('forms.store') }}" x-data="cdcFormBuilder()" class="space-y-6">
                @csrf

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4 text-indigo-700 border-b pb-2">
                            Informations du formulaire
                        </h3>

                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">
                                Nom du formulaire *
                            </label>
                            <input type="text"
                                   name="name"
                                   id="name"
                                   required
                                   value="{{ old('name', $duplicateData['name'] ?? '') }}"
                                   placeholder="Ex: Cahier des charges TPI 2025"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">
                                Description
                            </label>
                            <textarea name="description"
                                      id="description"
                                      rows="3"
                                      placeholder="Description du formulaire..."
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $duplicateData['description'] ?? '') }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="checkbox"
                                       name="is_active"
                                       value="1"
                                       {{ old('is_active', true) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-indigo-600 shadow-sm">
                                <span class="ml-2 text-sm text-gray-600">Formulaire actif</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4 text-indigo-700 border-b-2 border-indigo-200 pb-2">
                            1. INFORMATIONS GÉNÉRALES
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="candidat_nom" class="block text-sm font-medium text-gray-700">
                                    Nom du candidat *
                                </label>
                                <input type="text"
                                       name="candidat_nom"
                                       id="candidat_nom"
                                       required
                                       value="{{ old('candidat_nom') }}"
                                       placeholder="Nom de famille"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('candidat_nom')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="candidat_prenom" class="block text-sm font-medium text-gray-700">
                                    Prénom du candidat *
                                </label>
                                <input type="text"
                                       name="candidat_prenom"
                                       id="candidat_prenom"
                                       required
                                       value="{{ old('candidat_prenom') }}"
                                       placeholder="Prénom"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('candidat_prenom')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700">
                                Titre du CDC *
                            </label>
                            <input type="text"
                                   name="title"
                                   id="title"
                                   required
                                   value="{{ old('title') }}"
                                   placeholder="Ex: Cahier des charges TPI 2025 - Application web"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="lieu_travail" class="block text-sm font-medium text-gray-700">
                                Lieu de travail *
                            </label>
                            <input type="text"
                                   name="lieu_travail"
                                   id="lieu_travail"
                                   required
                                   value="{{ old('lieu_travail') }}"
                                   placeholder="Ex: ETML, Lausanne"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('lieu_travail')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="periode_realisation" class="block text-sm font-medium text-gray-700">
                                    Période de réalisation *
                                </label>
                                <input type="text"
                                       name="periode_realisation"
                                       id="periode_realisation"
                                       required
                                       value="{{ old('periode_realisation') }}"
                                       placeholder="Ex: Mai 2025"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('periode_realisation')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="horaire_travail" class="block text-sm font-medium text-gray-700">
                                    Horaire de travail *
                                </label>
                                <input type="text"
                                       name="horaire_travail"
                                       id="horaire_travail"
                                       required
                                       value="{{ old('horaire_travail') }}"
                                       placeholder="Ex: 8h-17h"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('horaire_travail')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="nombre_heures" class="block text-sm font-medium text-gray-700">
                                    Nombre d'heures *
                                </label>
                                <input type="text"
                                       name="nombre_heures"
                                       id="nombre_heures"
                                       required
                                       value="{{ old('nombre_heures') }}"
                                       placeholder="Ex: 80 heures"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('nombre_heures')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4 text-indigo-700 border-b pb-2">
                            2. CONTENU DU CAHIER DES CHARGES
                        </h3>

                        <div class="space-y-4 mb-4">
                            <template x-for="(field, index) in fields" :key="field.tempId">
                                <div class="border rounded-lg p-4 bg-gray-50 relative">
                                    <div class="absolute top-2 right-2 flex gap-2">
                                        <button type="button"
                                                @click="removeField(index)"
                                                class="p-2 text-red-600 hover:text-red-900 hover:bg-red-100 rounded transition"
                                                title="Supprimer">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pr-16">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">
                                                Nom du champ *
                                            </label>
                                            <input type="text"
                                                   :name="'fields[' + index + '][name]'"
                                                   x-model="field.name"
                                                   required
                                                   placeholder="Ex: titre_projet"
                                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">
                                                Label *
                                            </label>
                                            <input type="text"
                                                   :name="'fields[' + index + '][label]'"
                                                   x-model="field.label"
                                                   required
                                                   placeholder="Ex: Titre du projet"
                                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">
                                                Type de champ *
                                            </label>
                                            <select :name="'fields[' + index + '][field_type_id]'"
                                                    x-model="field.field_type_id"
                                                    required
                                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                                <option value="">Sélectionner un type</option>
                                                @foreach($fieldTypes as $type)
                                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">
                                                Placeholder
                                            </label>
                                            <input type="text"
                                                   :name="'fields[' + index + '][placeholder]'"
                                                   x-model="field.placeholder"
                                                   placeholder="Texte d'aide"
                                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                        </div>

                                        <div class="col-span-2">
                                            <label class="flex items-center">
                                                <input type="checkbox"
                                                       :name="'fields[' + index + '][is_required]'"
                                                       x-model="field.is_required"
                                                       value="1"
                                                       class="rounded border-gray-300 text-indigo-600 shadow-sm">
                                                <span class="ml-2 text-sm text-gray-600">Champ requis</span>
                                            </label>
                                        </div>

                                        <div class="col-span-2">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                <span class="flex items-center">
                                                    <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                    Contenu / Valeur
                                                </span>
                                            </label>
                                            <textarea :name="'fields[' + index + '][value]'"
                                                      x-model="field.value"
                                                      rows="4"
                                                      :placeholder="'Saisissez le contenu pour : ' + (field.label || 'ce champ')"
                                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"></textarea>
                                            <p class="mt-1 text-xs text-gray-500">Ce contenu sera inclus dans le CDC généré</p>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <div x-show="fields.length === 0" class="text-center py-12 bg-gray-100 rounded-lg">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="mt-4 text-gray-500">Aucun champ ajouté.</p>
                                <p class="mt-2 text-sm text-gray-400">Cliquez sur "Ajouter un champ" pour commencer.</p>
                            </div>
                        </div>

                        <button type="button"
                                @click="addField"
                                class="w-full inline-flex items-center justify-center px-4 py-3 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition font-medium">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Ajouter un champ
                        </button>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-green-50 to-emerald-50 overflow-hidden shadow-sm sm:rounded-lg border-2 border-green-200">
                    <div class="p-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-green-800">
                                    📋 Prêt à générer votre CDC
                                </h3>
                                <div class="mt-2 text-sm text-green-700">
                                    <p class="mb-2">En cliquant sur "Créer et générer le CDC", vous allez :</p>
                                    <ul class="list-disc list-inside space-y-1">
                                        <li>Créer un nouveau formulaire avec la structure définie</li>
                                        <li>Générer automatiquement un CDC Word basé sur vos données</li>
                                        <li>Pouvoir télécharger immédiatement le document</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-4">
                    <a href="{{ route('forms.index') }}"
                       class="inline-flex items-center px-6 py-3 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Annuler
                    </a>
                    <button type="submit"
                            class="inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-md hover:bg-green-700 transition font-medium shadow-lg">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
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
                        value: field.value || '',
                        is_required: field.is_required || false,
                        placeholder: field.placeholder || ''
                    }))
                    : [],

                tempIdCounter: Date.now() + (prefilledFields.length || 0),

                addField() {
                    this.fields.push({
                        tempId: this.tempIdCounter++,
                        name: '',
                        label: '',
                        placeholder: '',
                        is_required: false,
                        field_type_id: '',
                        value: ''
                    });
                    console.log('✅ Champ ajouté, total:', this.fields.length);
                },

                removeField(index) {
                    if (confirm('Êtes-vous sûr de vouloir supprimer ce champ ?')) {
                        this.fields.splice(index, 1);
                        console.log('🗑️ Champ supprimé, reste:', this.fields.length);
                    }
                }
            };
        }
    </script>
</x-app-layout>
