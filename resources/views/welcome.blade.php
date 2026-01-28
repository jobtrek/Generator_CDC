<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Générateur CDC') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,600,700" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white dark:bg-[#0a0a0a] min-h-screen flex flex-col items-center justify-center font-sans antialiased selection:bg-[#F53003] selection:text-white">

<div class="w-full max-w-xl px-6 text-center">
    <div class="relative py-12">

        <h1 class="text-6xl md:text-7xl font-extrabold tracking-tight text-gray-900 dark:text-white mb-6">
            CDC <span class="text-[#F53003]">Generator</span>
        </h1>

        <p class="text-lg text-gray-500 dark:text-gray-400 mb-10 max-w-sm mx-auto">
            Générez vos cahiers
        </p>

        <div class="flex flex-col items-center justify-center gap-4">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}"
                       class="group w-full rounded-2xl bg-gray-900 dark:bg-white px-8 py-5 text-lg font-bold text-white dark:text-black shadow-2xl transition-all duration-200 hover:scale-[1.02] active:scale-[0.98]">
                        Continuer vers le Dashboard
                        <span class="inline-block ml-2 transition-transform group-hover:translate-x-1">→</span>
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="group w-full rounded-2xl bg-[#F53003] px-8 py-5 text-lg font-bold text-white shadow-xl shadow-red-500/20 transition-all duration-200 hover:bg-[#d42a03] hover:scale-[1.02] active:scale-[0.98]">
                        Se connecter
                        <span class="inline-block ml-2 transition-transform group-hover:translate-x-1">→</span>
                    </a>
                @endauth
            @endif
        </div>

    </div>
</div>


</body>
</html>
