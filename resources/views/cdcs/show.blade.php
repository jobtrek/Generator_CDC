<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center">
                <a href="{{ route('cdcs.index') }}" class="mr-4 text-gray-600 hover:text-gray-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $cdc->title }}
                </h2>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('cdcs.download', $cdc) }}"
                   class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Télécharger .docx
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded flex items-center justify-between">
                    <span>{{ session('success') }}</span>
                    <button @click="show = false">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 text-indigo-700 border-b pb-2">
                        1. INFORMATIONS GÉNÉRALES
                    </h3>

                    <div class="space-y-4">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-medium text-gray-700 mb-3">Candidat</h4>
                            <div class="grid grid-cols-2 gap-3 text-sm">
                                <div>
                                    <span class="text-gray-500">Nom:</span>
                                    <span class="ml-2 font-medium">{{ $cdc->data['candidat_nom'] ?? 'Non renseigné' }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Prénom:</span>
                                    <span class="ml-2 font-medium">{{ $cdc->data['candidat_prenom'] ?? 'Non renseigné' }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-medium text-gray-700 mb-2">Lieu de travail</h4>
                            <p class="text-sm">{{ $cdc->data['lieu_travail'] ?? 'Non renseigné' }}</p>
                        </div>

                        @if(isset($cdc->data['chef_projet_nom']))
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-medium text-gray-700 mb-3">Chef de projet</h4>
                                <div class="grid grid-cols-2 gap-3 text-sm">
                                    <div>
                                        <span class="text-gray-500">Nom:</span>
                                        <span class="ml-2">{{ $cdc->data['chef_projet_nom'] ?? '' }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Prénom:</span>
                                        <span class="ml-2">{{ $cdc->data['chef_projet_prenom'] ?? '' }}</span>
                                    </div>
                                    @if(isset($cdc->data['chef_projet_email']))
                                        <div>
                                            <span class="text-gray-500">Email:</span>
                                            <span class="ml-2">{{ $cdc->data['chef_projet_email'] }}</span>
                                        </div>
                                    @endif
                                    @if(isset($cdc->data['chef_projet_telephone']))
                                        <div>
                                            <span class="text-gray-500">Téléphone:</span>
                                            <span class="ml-2">{{ $cdc->data['chef_projet_telephone'] }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        @if(isset($cdc->data['expert1_nom']) || isset($cdc->data['expert2_nom']))
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @if(isset($cdc->data['expert1_nom']))
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <h4 class="font-medium text-gray-700 mb-3">Expert 1</h4>
                                        <div class="space-y-2 text-sm">
                                            <div>
                                                <span class="text-gray-500">Nom:</span>
                                                <span class="ml-2">{{ $cdc->data['expert1_nom'] }}</span>
                                            </div>
                                            <div>
                                                <span class="text-gray-500">Prénom:</span>
                                                <span class="ml-2">{{ $cdc->data['expert1_prenom'] ?? '' }}</span>
                                            </div>
                                            @if(isset($cdc->data['expert1_email']))
                                                <div>
                                                    <span class="text-gray-500">Email:</span>
                                                    <span class="ml-2">{{ $cdc->data['expert1_email'] }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                @if(isset($cdc->data['expert2_nom']))
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <h4 class="font-medium text-gray-700 mb-3">Expert 2</h4>
                                        <div class="space-y-2 text-sm">
                                            <div>
                                                <span class="text-gray-500">Nom:</span>
                                                <span class="ml-2">{{ $cdc->data['expert2_nom'] }}</span>
                                            </div>
                                            <div>
                                                <span class="text-gray-500">Prénom:</span>
                                                <span class="ml-2">{{ $cdc->data['expert2_prenom'] ?? '' }}</span>
                                            </div>
                                            @if(isset($cdc->data['expert2_email']))
                                                <div>
                                                    <span class="text-gray-500">Email:</span>
                                                    <span class="ml-2">{{ $cdc->data['expert2_email'] }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-medium text-gray-700 mb-2 text-sm">Période de réalisation</h4>
                                <p class="text-sm">{{ $cdc->data['periode_realisation'] ?? 'Non renseigné' }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-medium text-gray-700 mb-2 text-sm">Horaire de travail</h4>
                                <p class="text-sm">{{ $cdc->data['horaire_travail'] ?? 'Non renseigné' }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-medium text-gray-700 mb-2 text-sm">Nombre d'heures</h4>
                                <p class="text-sm">{{ $cdc->data['nombre_heures'] ?? 'Non renseigné' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 text-indigo-700 border-b pb-2">
                        3. TITRE DU PROJET
                    </h3>
                    <p class="text-gray-900">{{ $cdc->title }}</p>
                </div>
            </div>

            @if(isset($cdc->data['materiel_logiciel']) && !empty($cdc->data['materiel_logiciel']))
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4 text-indigo-700 border-b pb-2">
                            4. MATÉRIEL ET LOGICIEL À DISPOSITION
                        </h3>
                        <ul class="list-disc list-inside space-y-1 text-gray-700">
                            @foreach(explode("\n", $cdc->data['materiel_logiciel']) as $item)
                                @if(trim($item))
                                    <li>{{ trim($item) }}</li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @if(isset($cdc->data['prerequis']) && !empty($cdc->data['prerequis']))
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4 text-indigo-700 border-b pb-2">
                            5. PRÉREQUIS
                        </h3>
                        <ul class="list-disc list-inside space-y-1 text-gray-700">
                            @foreach(explode("\n", $cdc->data['prerequis']) as $item)
                                @if(trim($item))
                                    <li>{{ trim($item) }}</li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 text-indigo-700 border-b pb-2">
                        6. DESCRIPTIF DU PROJET
                    </h3>
                    <div class="prose max-w-none text-gray-700">
                        {!! nl2br(e($cdc->data['descriptif_projet'] ?? 'Non renseigné')) !!}
                    </div>
                </div>
            </div>

            @if(isset($cdc->data['livrables']) && !empty($cdc->data['livrables']))
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4 text-indigo-700 border-b pb-2">
                            7. LIVRABLES
                        </h3>
                        <ul class="list-disc list-inside space-y-1 text-gray-700">
                            @foreach(explode("\n", $cdc->data['livrables']) as $item)
                                @if(trim($item))
                                    <li>{{ trim($item) }}</li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 text-indigo-700 border-b pb-2">
                        8. POINTS TECHNIQUES ÉVALUÉS
                    </h3>
                    <ol class="list-decimal list-inside space-y-2 text-gray-700">
                        @for($i = 1; $i <= 7; $i++)
                            <li>{{ $cdc->data['point_technique_' . $i] ?? '(à compléter par le chef de projet)' }}</li>
                        @endfor
                    </ol>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 text-indigo-700 border-b pb-2">
                        Informations
                    </h3>
                    <dl class="grid grid-cols-2 gap-4">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <dt class="text-sm font-medium text-gray-500">Créé par</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $cdc->user->name }}</dd>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <dt class="text-sm font-medium text-gray-500">Date de création</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $cdc->created_at->format('d/m/Y à H:i') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
