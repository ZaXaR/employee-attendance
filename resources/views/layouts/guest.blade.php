<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Sign in') }}</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs" defer></script>

</head>

<body class="min-h-screen bg-gradient-to-b from-gray-50 to-white text-gray-900 antialiased">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="w-full max-w-md">
            {{ $slot }}
        </div>
    </div>
</body>

</html>
