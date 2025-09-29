<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Créer un nouveau formulaire') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form method="POST" action="{{ route('forms.store') }}"
                          x-data="formBuilder({{ json_encode($sessionFields) }})"
                          @submit="reorderFields">
                        @csrf

                        <div class="mb-6">
                            <h3 class="text-lg font-medium mb-4">Informations du formulaire</h3>

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
                                <h3 class="text-lg font-medium">Champs du formulaire</h3>
                                <button type="button"
                                        @click="showPreview = !showPreview"
                                        class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                                    <span x-text="showPreview ? 'Masquer' : 'Prévisualiser'"></span>
                                </button>
                            </div>

                            <div class="space-y-4 mb-4" id="fields-container">
                                <template x-for="(field, index) in fields" :key="field.tempId">
                                    <div class="border rounded-lg p-4 bg-gray-50 relative">
                                        <div class="absolute top-2 right-2 flex gap-2">
                                            <button type="button"
                                                    @click="moveFieldUp(index)"
                                                    x-show="index > 0"
                                                    class="p-1 text-gray-600 hover:text-gray-900">
                                                ↑
                                            </button>
                                            <button type="button"
                                                    @click="moveFieldDown(index)"
                                                    x-show="index < fields.length - 1"
                                                    class="p-1 text-gray-600 hover:text-gray-900">
                                                ↓
                                            </button>
                                            <button type="button"
                                                    @click="removeField(index)"
                                                    class="p-1 text-red-600 hover:text-red-900">
                                                ✕
                                            </button>
                                        </div>

                                        <div class="grid grid-cols-2 gap-4 pr-20">
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

                                <div x-show="fields.length === 0" class="text-center py-8 text-gray-500">
                                    Aucun champ ajouté. Cliquez sur "Ajouter un champ" pour commencer.
                                </div>
                            </div>

                            <button type="button"
                                    @click="addField"
                                    class="w-full px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                + Ajouter un champ
                            </button>
                        </div>

                        <div x-show="showPreview"
                             x-transition
                             class="mb-6 p-6 bg-blue-50 rounded-lg border border-blue-200">
                            <h3 class="text-lg font-medium mb-4">Prévisualisation</h3>
                            <div class="space-y-4 bg-white p-6 rounded-lg">
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
                                               class="block w-full rounded-md border-gray-300 shadow-sm text-sm">

                                        <textarea x-show="field.input_type === 'textarea'"
                                                  :placeholder="field.placeholder"
                                                  disabled
                                                  rows="3"
                                                  class="block w-full rounded-md border-gray-300 shadow-sm text-sm"></textarea>

                                        <input x-show="field.input_type === 'date'"
                                               type="date"
                                               disabled
                                               class="block w-full rounded-md border-gray-300 shadow-sm text-sm">

                                        <select x-show="field.input_type === 'select'"
                                                disabled
                                                class="block w-full rounded-md border-gray-300 shadow-sm text-sm">
                                            <option>Sélectionner...</option>
                                            <template x-for="option in field.options">
                                                <option x-text="option"></option>
                                            </template>
                                        </select>

                                        <div x-show="field.input_type === 'checkbox'" class="space-y-2">
                                            <template x-for="option in field.options">
                                                <label class="flex items-center">
                                                    <input type="checkbox" disabled class="rounded border-gray-300">
                                                    <span class="ml-2 text-sm" x-text="option"></span>
                                                </label>
                                            </template>
                                        </div>
                                    </div>
                                </template>

                                <div x-show="fields.length === 0" class="text-center text-gray-500">
                                    Aucun champ à prévisualiser
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end gap-4">
                            <a href="{{ route('forms.index') }}"
                               class="px-6 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                                Annuler
                            </a>
                            <button type="submit"
                                    class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
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
                },

                reorderFields() {

                }
            };
        }
    </script>
</x-app-layout>
