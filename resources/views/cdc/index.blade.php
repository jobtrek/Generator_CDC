<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Cahiers des Charges') }}
            </h2>
            @can('cdc.create')
                <a href="{{ route('cdc.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Nouveau CDC
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div class="border rounded-lg p-4 hover:shadow-lg transition-shadow">
                            <h3 class="font-bold text-lg mb-2">CDC Exemple #1</h3>
                            <p class="text-gray-600 text-sm mb-4">Créé le 23/09/2025</p>
                            <div class="flex justify-between">
                                @can('cdc.view')
                                    <a href="{{ route('cdc.show', 1) }}" class="text-blue-600 hover:text-blue-800">
                                        Voir
                                    </a>
                                @endcan
                                @can('cdc.edit')
                                    <a href="{{ route('cdc.edit', 1) }}" class="text-green-600 hover:text-green-800">
                                        Modifier
                                    </a>
                                @endcan
                                @can('cdc.export')
                                    <a href="{{ route('cdc.export', 1) }}" class="text-purple-600 hover:text-purple-800">
                                        Exporter
                                    </a>
                                @endcan
                                @can('cdc.delete')
                                    <form method="POST" action="{{ route('cdc.destroy', 1) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                onclick="return confirm('Supprimer ce CDC ?')"
                                                class="text-red-600 hover:text-red-800">
                                            Supprimer
                                        </button>
                                    </form>
                                @endcan
                            </div>
                        </div>

                        <div class="col-span-full text-center py-8 text-gray-500">
                            <p>Aucun cahier des charges pour le moment.</p>
                            @can('cdc.create')
                                <a href="{{ route('cdc.create') }}" class="text-blue-600 hover:text-blue-800 mt-2 inline-block">
                                    Créer votre premier CDC
                                </a>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
