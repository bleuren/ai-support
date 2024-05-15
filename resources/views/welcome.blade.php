<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

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
            <!-- Inline Editing Input -->
            <div class="mt-4">
                <input type="text" id="customerQuestion" placeholder="問爆我"
                    class="w-full p-2 bg-gray-800 border border-gray-700 text-gray-300 rounded focus:outline-none focus:ring focus:border-blue-500">
            </div>
            <!-- Response Display -->
            <div class="mt-4" id="responseContainer"></div>
        </div>
    </div>
    <footer class="text-center py-4">
        <p class="text-sm text-gray-500">
            &copy; 2024 {{ config('app.name') }}. All Rights Reserved.
            Designed by <a href="https://bleu.tw" class="hover:underline font-bold text-gray-400">Bleu</a>.
        </p>
    </footer>

    <!-- JavaScript to handle the AJAX request -->
    <script>
        document.getElementById('customerQuestion').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault(); // Prevent form submission

                let question = e.target.value;
                let teamId = 1; // Replace with the actual team ID if needed

                fetch(`/support/${teamId}/answer`, {
                        method: 'POST', // Changed method from 'GET' to 'POST'
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        },
                        body: JSON.stringify({
                            question: question
                        }) // Changed from params to body and added JSON.stringify
                    })
                    .then(response => response.json())
                    .then(data => {
                        let responseContainer = document.getElementById('responseContainer');
                        responseContainer.innerHTML = `
                        <div class="p-4 mt-4 bg-gray-800 border border-gray-700 rounded">
                            <p class="text-gray-300">${data.response.response}</p>
                        </div>
                    `;
                    })
                    .catch(error => console.error('Error:', error));
            }
        });
    </script>
</body>

</html>
