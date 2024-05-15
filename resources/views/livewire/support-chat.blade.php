<div class="min-h-screen flex flex-col justify-center items-center bg-gray-900 text-gray-400">
    <div class="max-w-xl mx-auto p-6 sm:p-8">
        <div class="flex items-center space-x-4">
            <div class="text-lg text-gray-500 border-r border-gray-700 pr-4">
                Create Intelligens Inc
            </div>
            <div class="text-lg text-gray-500 uppercase tracking-wider">
                智能客服
            </div>
        </div>
        <div class="mt-4 relative">
            <input type="text" wire:model="question" wire:loading.attr="disabled" wire:keydown.enter="submit"
                placeholder="請輸入你的問題（請友善對待）"
                class="w-full p-2 bg-gray-800 border border-gray-700 text-gray-300 rounded focus:outline-none focus:ring focus:border-blue-500">
            <div wire:loading class="absolute inset-y-0 right-0 flex items-center pr-3">
                <svg class="animate-spin h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C6.477 0 0 6.477 0 12h4z"></path>
                </svg>
            </div>
        </div>
        @if ($response)
            <div class="mt-4 p-4 bg-gray-800 border border-gray-700 rounded">
                <p class="text-gray-300">{{ $response }}</p>
            </div>
        @endif
    </div>
    <footer class="text-center py-4">
        <p class="text-sm text-gray-500">
            &copy; 2024 {{ config('app.name') }}. All Rights Reserved.
            Designed by <a href="https://bleu.tw" class="hover:underline font-bold text-gray-400">Bleu</a>.
        </p>
    </footer>
</div>
