<div class="p-6 md:p-8 max-w-7xl mx-auto" wire:poll.5s>
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight">Dashboard Hari Ini</h2>
            <p class="text-slate-500 mt-1 font-medium">Ringkasan aktivitas pemindaian resi</p>
        </div>
        <div class="bg-white px-4 py-2 rounded-xl shadow-sm border border-slate-100 flex items-center gap-2">
            <svg class="w-5 h-5 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            <span class="text-sm font-bold text-slate-700">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>
        </div>
    </div>

    <!-- Metrik Utama -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        
        <!-- Sisa Target Card -->
        <div class="relative bg-gradient-to-br from-white to-slate-50 rounded-3xl p-6 shadow-sm border border-slate-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300 overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-slate-100 rounded-full opacity-50 group-hover:scale-110 transition-transform duration-500"></div>
            <div class="flex items-center gap-3 mb-4">
                <div class="p-3 bg-slate-100 rounded-2xl text-slate-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                </div>
                <h3 class="text-slate-500 font-bold uppercase tracking-wider text-xs">Sisa Target</h3>
            </div>
            <div class="mt-2 flex items-baseline gap-2 relative z-10">
                <span class="text-5xl font-black text-slate-800 tracking-tighter">{{ $totalUnscanned }}</span>
                <span class="text-sm font-semibold text-slate-400">resi</span>
            </div>
        </div>
        
        <!-- Total Scanned Card -->
        <div class="relative bg-gradient-to-br from-sky-500 to-blue-600 rounded-3xl p-6 shadow-md shadow-sky-500/20 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-white/10 rounded-full opacity-50 group-hover:scale-110 transition-transform duration-500"></div>
            <div class="flex items-center gap-3 mb-4 text-white">
                <div class="p-3 bg-white/20 backdrop-blur-sm rounded-2xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="font-bold uppercase tracking-wider text-xs text-sky-50">Telah Discan</h3>
            </div>
            <div class="mt-2 flex items-baseline gap-2 relative z-10">
                <span class="text-5xl font-black text-white tracking-tighter">{{ $totalScanned }}</span>
                <span class="text-sm font-medium text-sky-100">resi</span>
            </div>
        </div>

        <!-- Duplikat Card -->
        <div class="relative bg-gradient-to-br from-rose-500 to-red-600 rounded-3xl p-6 shadow-md shadow-rose-500/20 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-white/10 rounded-full opacity-50 group-hover:scale-110 transition-transform duration-500"></div>
            <div class="flex items-center gap-3 mb-4 text-white">
                <div class="p-3 bg-white/20 backdrop-blur-sm rounded-2xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="font-bold uppercase tracking-wider text-xs text-rose-50">Duplikat</h3>
            </div>
            <div class="mt-2 flex items-baseline gap-2 relative z-10">
                <span class="text-5xl font-black text-white tracking-tighter">{{ $totalDuplicates }}</span>
                <span class="text-sm font-medium text-rose-100">kali</span>
            </div>
        </div>

        <!-- Tidak Dikenal Card -->
        <div class="relative bg-gradient-to-br from-amber-400 to-orange-500 rounded-3xl p-6 shadow-md shadow-amber-500/20 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-white/10 rounded-full opacity-50 group-hover:scale-110 transition-transform duration-500"></div>
            <div class="flex items-center gap-3 mb-4 text-white">
                <div class="p-3 bg-white/20 backdrop-blur-sm rounded-2xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <h3 class="font-bold uppercase tracking-wider text-xs text-amber-50">Tidak Dikenal</h3>
            </div>
            <div class="mt-2 flex items-baseline gap-2 relative z-10">
                <span class="text-5xl font-black text-white tracking-tighter">{{ $totalUnknowns }}</span>
                <span class="text-sm font-medium text-amber-100">kali</span>
            </div>
        </div>
    </div>

    <!-- Progress Per Ekspedisi -->
    <h3 class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-2">
        <svg class="w-6 h-6 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
        Progress Pemindaian per Ekspedisi
    </h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($expeditionsData as $exp)
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6 hover:shadow-md transition-shadow">
            <div class="flex justify-between items-center mb-3">
                <h4 class="text-lg font-bold text-slate-700 flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-sky-500"></span>
                    {{ $exp->name }}
                </h4>
                <span class="text-sm font-black text-slate-800 bg-slate-100 px-3 py-1 rounded-full">
                    {{ $exp->total_scanned }} / <span class="text-slate-500">{{ $exp->total_target }}</span>
                </span>
            </div>
            <div class="w-full bg-slate-100 rounded-full h-3 mb-2 overflow-hidden">
                @php $expPercent = $exp->total_target > 0 ? ($exp->total_scanned / $exp->total_target) * 100 : 0; @endphp
                <div class="bg-gradient-to-r from-sky-400 to-blue-500 h-3 rounded-full transition-all duration-1000 ease-out relative" style="width: {{ $expPercent }}%">
                    <div class="absolute top-0 right-0 bottom-0 left-0 bg-[linear-gradient(45deg,rgba(255,255,255,0.15)_25%,transparent_25%,transparent_50%,rgba(255,255,255,0.15)_50%,rgba(255,255,255,0.15)_75%,transparent_75%,transparent)] bg-[length:1rem_1rem] animate-[stripes_1s_linear_infinite]"></div>
                </div>
            </div>
            <div class="flex justify-between text-xs font-semibold text-slate-400">
                <span>Progress</span>
                <span>{{ number_format($expPercent, 1) }}% Selesai</span>
            </div>
        </div>
        @endforeach
    </div>

    <style>
        @keyframes stripes {
            from { background-position: 1rem 0; }
            to { background-position: 0 0; }
        }
    </style>
</div>
