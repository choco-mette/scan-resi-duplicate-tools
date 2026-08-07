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
        
        /* Hide scrollbar for clean UI */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f8fafc; 
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1; 
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8; 
        }
    </style>
    @livewireStyles
</head>
<body class="bg-slate-50 text-slate-800 antialiased h-screen flex flex-col md:flex-row overflow-hidden" 
      x-data="{ isScanning: false }" 
      @scanning-started.window="isScanning = true" 
      @scanning-stopped.window="isScanning = false">

    @auth
    
    <!-- Sidebar / Top Navbar (Desktop Only) -->
    <aside class="hidden md:flex flex-col w-64 bg-slate-800 text-white shadow-xl h-screen flex-shrink-0 z-30">
        <div class="p-6 flex items-center justify-center border-b border-slate-700">
            <h1 class="text-2xl font-black tracking-wider flex items-center gap-2 text-slate-100">
                <svg class="w-8 h-8 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                SCAN RESI
            </h1>
        </div>
        
        <div class="p-4 border-b border-slate-700 bg-slate-900/40">
            <div class="text-sm font-semibold text-slate-200">{{ auth()->user()->name }}</div>
            <div class="text-xs text-slate-400">{{ auth()->user()->role }}</div>
        </div>

        <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('dashboard') ? 'bg-slate-700 text-sky-400 font-bold' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Dashboard
            </a>
            <a href="{{ route('import') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('import') ? 'bg-slate-700 text-sky-400 font-bold' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                Import Data
            </a>
            <a href="{{ route('scanner') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('scanner') ? 'bg-slate-700 text-sky-400 font-bold' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Scan Resi
            </a>
            <a href="{{ route('history') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('history') ? 'bg-slate-700 text-sky-400 font-bold' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Riwayat
            </a>
        </nav>
        
        <div class="p-4 border-t border-slate-700">
            <form action="/logout" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 bg-slate-700 hover:bg-slate-600 text-slate-200 px-4 py-3 rounded-lg transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Top Header (Mobile Only) -->
    <header class="md:hidden bg-slate-800 text-white p-4 shadow-md flex justify-between items-center z-30">
        <h1 class="text-xl font-bold tracking-wide flex items-center gap-2">
            <svg class="w-6 h-6 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Scan Resi
        </h1>
        <div class="flex items-center gap-3 text-sm">
            <span class="font-medium bg-slate-700 px-2 py-1 rounded text-slate-200">{{ auth()->user()->name }}</span>
            <form action="/logout" method="POST" class="inline">
                @csrf
                <button type="submit" class="text-slate-400 hover:text-white transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                </button>
            </form>
        </div>
    </header>
    @endauth

    <!-- Main Content Area -->
    <main class="flex-1 overflow-y-auto w-full @auth pb-20 md:pb-0 @endauth h-full flex flex-col">
        {{ $slot }}
    </main>

    <!-- Bottom Navigation Bar (Mobile Only) -->
    @auth
    <nav class="md:hidden fixed bottom-0 w-full bg-white shadow-[0_-10px_20px_rgba(0,0,0,0.1)] border-t border-slate-100 grid grid-cols-4 pb-6 pt-2 px-2 z-30">
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center p-2 {{ request()->routeIs('dashboard') ? 'text-sky-600' : 'text-slate-400 hover:text-sky-500' }}">
            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            <span class="text-[10px] font-medium">Dashboard</span>
        </a>
        <a href="{{ route('import') }}" class="flex flex-col items-center p-2 {{ request()->routeIs('import') ? 'text-sky-600' : 'text-slate-400 hover:text-sky-500' }}">
            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
            <span class="text-[10px] font-medium">Import</span>
        </a>
        <a href="{{ route('scanner') }}" class="flex flex-col items-center justify-center p-2 {{ request()->routeIs('scanner') ? 'text-sky-600' : 'text-slate-400 hover:text-sky-500' }}">
            <div class="{{ request()->routeIs('scanner') ? 'bg-sky-100 text-sky-600' : 'bg-slate-100 text-slate-500' }} p-2 rounded-xl mb-1">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            </div>
            <span class="text-[10px] font-medium">Scan</span>
        </a>
        <a href="{{ route('history') }}" class="flex flex-col items-center p-2 {{ request()->routeIs('history') ? 'text-sky-600' : 'text-slate-400 hover:text-sky-500' }}">
            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="text-[10px] font-medium">History</span>
        </a>
    </nav>
    @endauth

    @livewireScripts
</body>
</html>
