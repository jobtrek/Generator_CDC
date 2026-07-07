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
                $cdc = $form->cdc;
                $cdcData = $cdc ? $cdc->data : [];
                $getValue = function ($key, $default = '') use ($cdcData) {
                    return old($key, $cdcData[$key] ?? $default);
                };
                $orientationDefault = '';
                $procedureDefault = '';
            @endphp

            <form method="POST" action="{{ route('forms.update', $form) }}" x-data="cdcFormBuilder()" class="space-y-6">
                @csrf
                @method('PUT')

                <input type="hidden" name="name" value="{{ old('name', $form->name) }}">

                @include('forms.partials.cdc-fields')

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
</x-app-layout>
