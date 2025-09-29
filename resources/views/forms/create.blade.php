<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('forms.index') }}" class="mr-4 text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Créer un nouveau formulaire
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form method="POST" action="{{ route('forms.store') }}"
                          x-data="formBuilder({{ json_encode($sessionFields) }})"
                          @submit.prevent="validateAndSubmit">
                        @csrf

                        <div x-show="showNotification"
                             x-transition
                             class="mb-4 p-4 rounded-lg flex items-center"
                             :class="notificationType === 'error' ? 'bg-red-100 border-l-4 border-red-500 text-red-700' : 'bg-green-100 border-l-4 border-green-500 text-green-700'">
                            <svg x-show="notificationType === 'error'" class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <svg x-show="notificationType === 'success'" class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span x-text="notificationMessage"></span>
                        </div>

                        <div class="mb-6">
                            <h3 class="text-lg font-medium mb-4 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
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
                                       value="{{ old('name') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('name')
                                <p class="mt-1 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="description" class="block text-sm font-medium text-gray-700">
                                    Description
                                </label>
                                <textarea name="description"
                                          id="description"
                                          rows="3"
                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
                                @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="flex items-center">
                                    <input type="checkbox"
                                           name="is_active"
                                           value="1"
                                           checked
                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-600">Formulaire actif</span>
                                </label>
                            </div>
                        </div>

                        <hr class="my-6">

                        <div class="mb-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-medium flex items-center">
                                    <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    Champs du formulaire
                                </h3>
                                <button type="button"
                                        @click="showPreview = !showPreview"
                                        class="inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition">
                                    <svg x-show="!showPreview" class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    <svg x-show="showPreview" class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                    </svg>
                                    <span x-text="showPreview ? 'Masquer l\'aperçu' : 'Prévisualiser'"></span>
                                </button>
                            </div>

                            <div class="space-y-4 mb-4" id="fields-container">
                                <template x-for="(field, index) in fields" :key="field.tempId">
                                    <div class="border rounded-lg p-4 bg-gray-50 relative hover:shadow-md transition">
                                        <div class="absolute top-2 right-2 flex gap-2">
                                            <button type="button"
                                                    @click="moveFieldUp(index)"
                                                    x-show="index > 0"
                                                    class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-200 rounded transition"
                                                    title="Déplacer vers le haut">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                                </svg>
                                            </button>
                                            <button type="button"
                                                    @click="moveFieldDown(index)"
                                                    x-show="index < fields.length - 1"
                                                    class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-200 rounded transition"
                                                    title="Déplacer vers le bas">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                </svg>
                                            </button>
                                            <button type="button"
                                                    @click="removeField(index)"
                                                    class="p-2 text-red-600 hover:text-red-900 hover:bg-red-100 rounded transition"
                                                    title="Supprimer">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </div>

                                        <div class="grid grid-cols-2 gap-4 pr-24">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">
                                                    Nom du champ *
                                                </label>
                                                <input type="text"
                                                       :name="'fields[' + index + '][name]'"
                                                       x-model="field.name"
                                                       required
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
                                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                            </div>

                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">
                                                    Type de champ *
                                                </label>
                                                <select :name="'fields[' + index + '][field_type_id]'"
                                                        x-model="field.field_type_id"
                                                        @change="updateFieldType(index)"
                                                        required
                                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                                    <option value="">Sélectionner un type</option>
                                                    @foreach($fieldTypes as $type)
                                                        <option value="{{ $type->id }}" data-input-type="{{ $type->input_type }}">
                                                            {{ $type->name }}
                                                        </option>
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
                                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                            </div>

                                            <div class="col-span-2">
                                                <label class="flex items-center">
                                                    <input type="checkbox"
                                                           :name="'fields[' + index + '][is_required]'"
                                                           x-model="field.is_required"
                                                           value="1"
                                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                    <span class="ml-2 text-sm text-gray-600">Champ requis</span>
                                                </label>
                                            </div>

                                            <div class="col-span-2" x-show="field.input_type === 'select' || field.input_type === 'checkbox'">
                                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                                    Options (une par ligne)
                                                </label>
                                                <textarea :name="'fields[' + index + '][options_text]'"
                                                          x-model="field.optionsText"
                                                          @input="updateOptions(index)"
                                                          rows="3"
                                                          placeholder="Option 1&#10;Option 2&#10;Option 3"
                                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"></textarea>
                                                <input type="hidden"
                                                       :name="'fields[' + index + '][options]'"
                                                       :value="JSON.stringify(field.options)">
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                <div x-show="fields.length === 0" class="text-center py-12 bg-gray-100 rounded-lg">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <p class="mt-4 text-gray-500">Aucun champ ajouté. Cliquez sur "Ajouter un champ" pour commencer.</p>
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

                        <div x-show="showPreview"
                             x-transition
                             class="mb-6 p-6 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border border-blue-200">
                            <h3 class="text-lg font-medium mb-4 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Prévisualisation
                            </h3>
                            <div class="space-y-4 bg-white p-6 rounded-lg shadow">
                                <template x-for="(field, index) in fields" :key="field.tempId">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            <span x-text="field.label"></span>
                                            <span x-show="field.is_required" class="text-red-500">*</span>
                                        </label>

                                        <input x-show="field.input_type === 'text' || field.input_type === 'email' || field.input_type === 'number'"
                                               :type="field.input_type"
                                               :placeholder="field.placeholder"
                                               disabled
                                               class="block w-full rounded-md border-gray-300 shadow-sm text-sm bg-gray-50">

                                        <textarea x-show="field.input_type === 'textarea'"
                                                  :placeholder="field.placeholder"
                                                  disabled
                                                  rows="3"
                                                  class="block w-full rounded-md border-gray-300 shadow-sm text-sm bg-gray-50"></textarea>

                                        <input x-show="field.input_type === 'date'"
                                               type="date"
                                               disabled
                                               class="block w-full rounded-md border-gray-300 shadow-sm text-sm bg-gray-50">

                                        <select x-show="field.input_type === 'select'"
                                                disabled
                                                class="block w-full rounded-md border-gray-300 shadow-sm text-sm bg-gray-50">
                                            <option>Sélectionner...</option>
                                            <template x-for="option in field.options">
                                                <option x-text="option"></option>
                                            </template>
                                        </select>

                                        <div x-show="field.input_type === 'checkbox'" class="space-y-2">
                                            <template x-for="option in field.options">
                                                <label class="flex items-center">
                                                    <input type="checkbox" disabled class="rounded border-gray-300 bg-gray-50">
                                                    <span class="ml-2 text-sm" x-text="option"></span>
                                                </label>
                                            </template>
                                        </div>
                                    </div>
                                </template>

                                <div x-show="fields.length === 0" class="text-center text-gray-500 py-8">
                                    Aucun champ à prévisualiser
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end gap-4 pt-6 border-t">
                            <a href="{{ route('forms.index') }}"
                               class="inline-flex items-center px-6 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Annuler
                            </a>
                            <button type="submit"
                                    class="inline-flex items-center px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition font-medium">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Créer le formulaire
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script>
        function formBuilder(initialFields = []) {
            return {
                fields: initialFields.length > 0 ? initialFields : [],
                showPreview: false,
                tempIdCounter: Date.now(),
                showNotification: false,
                notificationType: '',
                notificationMessage: '',

                showNotif(type, message) {
                    this.notificationType = type;
                    this.notificationMessage = message;
                    this.showNotification = true;
                    setTimeout(() => {
                        this.showNotification = false;
                    }, 5000);
                },

                validateAndSubmit(event) {
                    if (this.fields.length === 0) {
                        this.showNotif('error', 'Veuillez ajouter au moins un champ au formulaire.');
                        return false;
                    }
                    event.target.submit();
                },

                addField() {
                    this.fields.push({
                        tempId: this.tempIdCounter++,
                        name: '',
                        label: '',
                        placeholder: '',
                        is_required: false,
                        field_type_id: '',
                        input_type: '',
                        options: [],
                        optionsText: ''
                    });
                },

                removeField(index) {
                    if (confirm('Êtes-vous sûr de vouloir supprimer ce champ ?')) {
                        this.fields.splice(index, 1);
                    }
                },

                moveFieldUp(index) {
                    if (index > 0) {
                        const temp = this.fields[index];
                        this.fields[index] = this.fields[index - 1];
                        this.fields[index - 1] = temp;
                    }
                },

                moveFieldDown(index) {
                    if (index < this.fields.length - 1) {
                        const temp = this.fields[index];
                        this.fields[index] = this.fields[index + 1];
                        this.fields[index + 1] = temp;
                    }
                },

                updateFieldType(index) {
                    const select = event.target;
                    const selectedOption = select.options[select.selectedIndex];
                    this.fields[index].input_type = selectedOption.dataset.inputType || '';
                },

                updateOptions(index) {
                    const text = this.fields[index].optionsText;
                    this.fields[index].options = text
                        .split('\n')
                        .map(line => line.trim())
                        .filter(line => line.length > 0);
                }
            };
        }
    </script>
</x-app-layout>
