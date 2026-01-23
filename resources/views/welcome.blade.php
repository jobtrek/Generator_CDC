<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Générateur CDC') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,600,700" rel="stylesheet" />

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
</head>
<body class="bg-gray-50 dark:bg-[#0a0a0a] min-h-screen flex flex-col items-center justify-center font-sans antialiased selection:bg-[#F53003] selection:text-white">

<div class="w-full max-w-7xl px-4 sm:px-6 lg:px-8 text-center">
    <div class="relative py-16 sm:py-24">

        <h1 class="text-5xl md:text-7xl font-bold tracking-tight text-gray-900 dark:text-white mb-8">
            Générateur <span class="text-[#F53003]">CDC</span>
        </h1>

        <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4 sm:gap-6">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}"
                       class="w-full sm:w-auto rounded-xl bg-[#F53003] px-8 py-4 text-base font-semibold text-white shadow-lg shadow-red-500/30 hover:bg-[#d42a03] hover:shadow-red-500/40 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#F53003] transition-all duration-200 transform hover:-translate-y-0.5">
                        Accéder au Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="w-full sm:w-auto rounded-xl bg-[#F53003] px-8 py-4 text-base font-semibold text-white shadow-lg shadow-red-500/30 hover:bg-[#d42a03] hover:shadow-red-500/40 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#F53003] transition-all duration-200 transform hover:-translate-y-0.5">
                        Se connecter
                    </a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                           class="w-full sm:w-auto rounded-xl bg-white dark:bg-white/10 px-8 py-4 text-base font-semibold text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-white/20 hover:bg-gray-50 dark:hover:bg-white/20 transition-all duration-200 transform hover:-translate-y-0.5">
                            S'inscrire
                        </a>
                    @endif
                @endauth
            @endif
        </div>

    </div>
</div>

</body>
</html>
