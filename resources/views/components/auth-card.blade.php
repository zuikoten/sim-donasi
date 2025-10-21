<div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-xl overflow-hidden sm:rounded-lg border border-gray-200">
    <!-- Tambahkan header gradien agar lebih menarik -->
    <div class="h-2 bg-gradient-to-r from-blue-500 to-purple-600"></div>
    
    <!-- Ini adalah bagian penting: div ini akan menengahkan semua isinya -->
    <div class="mt-4 flex flex-col items-center">
        {{ $slot }}
    </div>
</div>