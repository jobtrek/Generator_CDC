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

            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>{{ session('error') }}</span>
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

            <!-- Barre de progression -->
            <div class="sticky top-0 z-40 mb-6 h-1 bg-gray-200 overflow-hidden">
                <div id="progress-fill" class="h-full transition-all duration-700 ease-out" style="width: 0%; background: linear-gradient(90deg, #6366f1, #10b981)"></div>
            </div>

            @php
                $cdcData = [];
                $getValue = function ($key, $default = '') use ($cdcData) {
                    return old($key, $cdcData[$key] ?? $default);
                };
                $orientationDefault = '88601 Développement d\'applications';
                $procedureDefault = "Le candidat réalise un travail personnel sur la base d'un cahier des charges reçu le 1er jour.\n\n"
                    . "Le cahier des charges est approuvé par les deux experts. Il est en outre présenté, commenté et discuté avec le candidat. Par sa signature, le candidat accepte le travail proposé.\n\n"
                    . "Le candidat a connaissance de la feuille d'évaluation avant de débuter le travail.\n\n"
                    . "Le candidat est entièrement responsable de la sécurité de ses données.\n\n"
                    . "En cas de problèmes graves, le candidat avertit au plus vite les deux experts et son CdP.\n\n"
                    . "Le candidat a la possibilité d'obtenir de l'aide, mais doit le mentionner dans son dossier.\n\n"
                    . "A la fin du délai imparti pour la réalisation du TPI, le candidat doit transmettre par courrier électronique le dossier de projet aux deux experts et au chef de projet.";
            @endphp

            <form id="cdc-form" method="POST" action="{{ route('forms.store') }}" data-autosave-url="{{ route('forms.autosave') }}" x-data="{ ...cdcFormBuilder(), submitting: false }" x-on:submit="if(submitting) { $event.preventDefault(); return; } submitting = true;" class="space-y-6">
                @csrf

                @include('forms.partials.cdc-fields')

                <!-- Brouillon indicateur -->
                <div class="flex justify-between items-center">
                    <div id="autosave-indicator"
                         class="hidden items-center gap-2 px-3 py-1.5 rounded-lg border text-xs font-medium transition-all duration-300">
                        <span id="autosave-icon"></span>
                        <span id="autosave-text"></span>
                    </div>
                    <div class="flex gap-4">
                        <a href="{{ route('forms.index') }}"
                           class="px-6 py-3 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition">
                            Annuler
                        </a>
                        <button type="submit"
                                :disabled="submitting"
                                :class="submitting ? 'opacity-50 cursor-not-allowed' : 'hover:bg-green-700'"
                                class="px-6 py-3 bg-green-600 text-white rounded-md transition font-medium shadow-lg">
                            <span x-show="!submitting">Créer le cahier des charges</span>
                            <span x-show="submitting" x-cloak>Création en cours...</span>
                        </button>
                    </div>
                </div>

                <input type="hidden" name="draft_form_id" id="draft_form_id" value="{{ $draftFormId ?? '' }}">
            </form>
        </div>
    </div>
    <script src="{{ asset('js/phone-formatter.js') }}"></script>
    <script src="{{ asset('js/cdc-autosave.js') }}"></script>
    <script src="{{ asset('js/cdc-progress.js') }}"></script>
</x-app-layout>
