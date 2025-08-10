@props(['header' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')

        @if (View::hasSection('header'))
            <header class="bg-white shadow mb-6">
                <div class="max-w-7xl mx-auto py-4 px-6 lg:px-8">
                    @yield('header')
                </div>
            </header>
        @endif

        <main>
            {{ $slot }}
        </main>
    </div>
</body>

</html>
