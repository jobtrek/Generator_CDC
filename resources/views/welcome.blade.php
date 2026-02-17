<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Générateur CDC') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .floating-card {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        .gradient-bg {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
        }
    </style>
</head>
<body class="bg-white dark:bg-slate-950 min-h-screen font-sans antialiased selection:bg-indigo-600 selection:text-white">

<!-- Navigation -->
<nav class="sticky top-0 z-50 backdrop-blur-md bg-white/80 dark:bg-slate-950/80 border-b border-gray-200 dark:border-slate-800">
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
        <div class="flex items-center gap-2">
            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center">
                <span class="text-white font-bold text-lg">C</span>
            </div>
            <span class="font-bold text-xl text-gray-900 dark:text-white">CDC Generator</span>
        </div>

        <div class="flex items-center gap-4">
            @auth
                <a href="{{ url('/dashboard') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white font-medium transition">
                    Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white font-medium transition">
                    Se connecter
                </a>
            @endauth
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="relative overflow-hidden px-6 py-20 md:py-32">
    <!-- Animated Background -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-0 right-0 w-96 h-96 bg-gradient-to-br from-indigo-300 to-purple-300 rounded-full opacity-20 blur-3xl -mr-48 -mt-48 dark:opacity-10"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-gradient-to-tr from-indigo-300 to-blue-300 rounded-full opacity-20 blur-3xl -ml-48 -mb-48 dark:opacity-10"></div>
    </div>

    <div class="relative max-w-6xl mx-auto">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <!-- Left Content -->
            <div>
                <h1 class="text-5xl md:text-6xl font-bold leading-tight mb-6 text-gray-900 dark:text-white">
                    Générez vos <span class="gradient-text">cahiers des charges</span> simplement
                </h1>

                <p class="text-xl text-gray-600 dark:text-gray-400 mb-8 leading-relaxed">
                    Créez, éditez et gérez vos cahiers des charges de manière professionnelle.
                </p>

                <div class="flex flex-col sm:flex-row gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="group px-8 py-4 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-semibold transition-all duration-200 hover:shadow-xl hover:scale-105 active:scale-95 text-center">
                            Accéder au Dashboard
                            <span class="inline-block ml-2 transition-transform group-hover:translate-x-1">→</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="group px-8 py-4 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-semibold transition-all duration-200 hover:shadow-xl hover:scale-105 active:scale-95 text-center">
                            Se connecter
                            <span class="inline-block ml-2 transition-transform group-hover:translate-x-1">→</span>
                        </a>
                    @endauth
                </div>

                <!-- Features List -->
                <div class="mt-12 grid sm:grid-cols-2 gap-4">
                    <div class="flex gap-3">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-8 w-8 rounded-lg bg-indigo-100 dark:bg-indigo-900">
                                <svg class="h-5 w-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white">Interface Intuitive</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Facile à utiliser</p>
                        </div>
                    </div>


                    <div class="flex gap-3">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-8 w-8 rounded-lg bg-indigo-100 dark:bg-indigo-900">
                                <svg class="h-5 w-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white">Export Facile</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Format professionnel</p>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Right Illustration -->
            <div class="hidden md:block">
                <div class="floating-card">
                    <div class="gradient-bg rounded-2xl p-8 border border-indigo-100 dark:border-indigo-900">
                        <div class="space-y-4">
                            <div class="h-4 bg-gradient-to-r from-indigo-200 to-purple-200 dark:from-indigo-900 dark:to-purple-900 rounded w-3/4"></div>
                            <div class="h-4 bg-gradient-to-r from-indigo-200 to-purple-200 dark:from-indigo-900 dark:to-purple-900 rounded w-5/6"></div>
                            <div class="h-4 bg-gradient-to-r from-indigo-200 to-purple-200 dark:from-indigo-900 dark:to-purple-900 rounded w-4/5"></div>
                        </div>
                        <div class="mt-6 pt-6 border-t border-indigo-200 dark:border-indigo-800">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="h-20 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center">
                                    <span class="text-sm font-semibold text-indigo-600 dark:text-indigo-400">50%</span>
                                </div>
                                <div class="h-20 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                                    <span class="text-sm font-semibold text-purple-600 dark:text-purple-400">100h</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="border-t border-gray-200 dark:border-slate-800 px-6 py-8 bg-gray-50 dark:bg-slate-900">
    <div class="max-w-6xl mx-auto flex justify-between items-center flex-col sm:flex-row gap-4">
        <p class="text-gray-600 dark:text-gray-400 text-sm">
            © 2024 CDC Generator. Tous droits réservés.
        </p>
    </div>
</footer>

</body>
</html>
