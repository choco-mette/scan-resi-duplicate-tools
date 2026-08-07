<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $title ?? 'Scan Resi Tools' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Kiosk Mode utilities */
        .large-touch-target { min-height: 60px; min-width: 60px; }
    </style>
    @livewireStyles
</head>
<body class="bg-gray-100 text-gray-800 antialiased h-screen flex flex-col">

    <!-- Top Navigation (Admin) or Minimal Header (Scanner) -->
    <header class="bg-indigo-600 text-white p-4 shadow-md flex justify-between items-center">
        <h1 class="text-xl font-bold">Scan Resi</h1>
        @auth
        <div class="text-sm">
            {{ auth()->user()->name }} ({{ auth()->user()->role }})
        </div>
        @endauth
    </header>

    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto p-4 pb-24">
        {{ $slot }}
    </main>

    <!-- Bottom Navigation Bar (Mobile) -->
    @auth
    <nav class="fixed bottom-0 w-full bg-white shadow-[0_-2px_10px_rgba(0,0,0,0.1)] flex justify-around p-3 pb-safe">
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center {{ request()->routeIs('dashboard') ? 'text-indigo-600' : 'text-gray-500 hover:text-indigo-600' }}">
            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            <span class="text-xs">Dashboard</span>
        </a>
        <a href="{{ route('import') }}" class="flex flex-col items-center {{ request()->routeIs('import') ? 'text-indigo-600' : 'text-gray-500 hover:text-indigo-600' }}">
            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
            <span class="text-xs">Import</span>
        </a>
        <a href="{{ route('scanner') }}" class="flex flex-col items-center {{ request()->routeIs('scanner') ? 'text-indigo-600 font-bold' : 'text-gray-500 hover:text-indigo-600' }}">
            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span class="text-xs font-bold">Scan</span>
        </a>
        <a href="{{ route('history') }}" class="flex flex-col items-center {{ request()->routeIs('history') ? 'text-indigo-600' : 'text-gray-500 hover:text-indigo-600' }}">
            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="text-xs">History</span>
        </a>
    </nav>
    @endauth

    @livewireScripts
</body>
</html>
