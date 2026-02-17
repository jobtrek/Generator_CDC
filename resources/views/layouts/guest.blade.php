<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'CDC Generator') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-white dark:bg-slate-950 antialiased">
<!-- Animated Background -->
<div class="fixed inset-0 overflow-hidden pointer-events-none">
    <div class="absolute top-0 right-0 w-96 h-96 bg-gradient-to-br from-indigo-300 to-purple-300 rounded-full opacity-10 blur-3xl -mr-48 -mt-48"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 bg-gradient-to-tr from-indigo-300 to-blue-300 rounded-full opacity-10 blur-3xl -ml-48 -mb-48"></div>
</div>

<!-- Page Content -->
{{ $slot }}
</body>
</html>
