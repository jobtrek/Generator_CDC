<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $form->name }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('forms.edit', $form) }}"
                   class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Modifier
                </a>
                <form method="POST"
                      action="{{ route('forms.destroy', $form) }}"
                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce formulaire ?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                        Supprimer
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif


            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Informations</h3>

                    <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Description</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $form->description ?? 'Aucune description' }}
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Statut</dt>
                            <dd class="mt-1">
                                @if($form->is_active)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Actif
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        Inactif
                                    </span>
                                @endif
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Créé par</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $form->user->name }}
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Date de création</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $form->created_at->format('d/m/Y à H:i') }}
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Dernière modification</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $form->updated_at->format('d/m/Y à H:i') }}
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nombre de champs</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $form->fields->count() }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>


            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Champs du formulaire</h3>

                    @if($form->fields->count() > 0)
                        <div class="space-y-4">
                            @foreach($form->fields as $field)
                                <div class="border rounded-lg p-4 bg-gray-50">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-2">
                                                <h4 class="text-md font-semibold text-gray-900">
                                                    {{ $field->label }}
                                                </h4>
                                                @if($field->is_required)
                                                    <span class="text-red-500 text-sm">*</span>
                                                @endif
                                                <span class="px-2 py-1 text-xs bg-indigo-100 text-indigo-800 rounded">
                                                    {{ $field->fieldType->name }}
                                                </span>
                                            </div>

                                            <dl class="grid grid-cols-2 gap-2 text-sm">
                                                <div>
                                                    <dt class="text-gray-500">Nom du champ:</dt>
                                                    <dd class="text-gray-900 font-mono">{{ $field->name }}</dd>
                                                </div>

                                                @if($field->placeholder)
                                                    <div>
                                                        <dt class="text-gray-500">Placeholder:</dt>
                                                        <dd class="text-gray-900">{{ $field->placeholder }}</dd>
                                                    </div>
                                                @endif

                                                <div>
                                                    <dt class="text-gray-500">Ordre:</dt>
                                                    <dd class="text-gray-900">{{ $field->order_index + 1 }}</dd>
                                                </div>

                                                <div>
                                                    <dt class="text-gray-500">Requis:</dt>
                                                    <dd class="text-gray-900">
                                                        {{ $field->is_required ? 'Oui' : 'Non' }}
                                                    </dd>
                                                </div>
                                            </dl>

                                                @if($field->options && is_array($field->options) && count($field->options) > 0)
                                                <div class="mt-2">
                                                    <dt class="text-sm text-gray-500">Options:</dt>
                                                    <dd class="mt-1">
                                                        <ul class="list-disc list-inside text-sm text-gray-900">
                                                            @foreach($field->options as $option)
                                                                <li>{{ $option }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </dd>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            Aucun champ défini pour ce formulaire.
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
