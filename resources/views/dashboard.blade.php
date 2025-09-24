<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4">Bienvenue, {{ Auth::user()->name }} !</h3>

                    <div class="mb-4">
                        <p class="text-sm text-gray-600">Votre rôle :</p>
                        @foreach(Auth::user()->getRoleNames() as $role)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                @if($role == 'super-admin') bg-red-100 text-red-800
                                @elseif($role == 'admin') bg-orange-100 text-orange-800
                                @elseif($role == 'manager') bg-blue-100 text-blue-800
                                @elseif($role == 'user') bg-green-100 text-green-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst($role) }}
                            </span>
                        @endforeach
                    </div>

                    <div>
                        <p class="text-sm text-gray-600 mb-2">Vos permissions :</p>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                            @foreach(Auth::user()->getAllPermissions() as $permission)
                                <span class="text-xs bg-gray-100 px-2 py-1 rounded">
                                    {{ $permission->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
