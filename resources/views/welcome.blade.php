<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ env('APP_NAME') }}</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

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
    <div class="flex-grow flex items-center justify-center">
        <div class="max-w-xl mx-auto p-6 sm:p-8">
            <div class="flex items-center space-x-4">
                <div class="text-lg text-gray-500 border-r border-gray-700 pr-4">
                    Create Intelligens Inc
                </div>
                <div class="text-lg text-gray-500 uppercase tracking-wider">
                    智能客服
                </div>
            </div>
        </div>
    </div>
    <footer class="text-center py-4">
        <p class="text-sm text-gray-500">
            &copy; 2024 {{ config('app.name') }}. All Rights Reserved.
            Designed by <a href="https://bleu.tw" class="hover:underline font-bold text-gray-400">Bleu</a>.
        </p>
    </footer>
</body>

</html>
