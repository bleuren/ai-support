<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ env('APP_NAME') }}</title>

    <!-- Tailwind CSS CDN -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Livewire Styles -->
    @livewireStyles

    <!-- Dark Mode Setup -->
    <style>
        @media (prefers-color-scheme: dark) {
            html {
                color-scheme: dark;
            }
        }

        body {
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
        }
    </style>
</head>

<body class="antialiased bg-gray-900 text-gray-400 min-h-screen flex flex-col justify-center">
    {{ $slot }}

    <!-- Livewire Scripts -->
    @livewireScripts
</body>

</html>
