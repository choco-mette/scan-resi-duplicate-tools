<div class="p-4 md:p-6 max-w-7xl mx-auto">
    <div class="mb-6 flex flex-col md:flex-row justify-between md:items-end gap-4">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">Riwayat Scan</h2>
            <p class="text-gray-500">Daftar semua resi yang pernah dipindai</p>
        </div>
        
        <div class="flex flex-col md:flex-row gap-3">
            <input wire:model.live="search" type="text" placeholder="Cari No. Resi..." class="border border-slate-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-sky-500 text-sm">
            
            <input wire:model.live="dateFilter" type="date" class="border border-slate-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-sky-500 text-sm bg-white text-slate-600">

            <select wire:model.live="statusFilter" class="border border-slate-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-sky-500 bg-white text-sm">
                <option value="">Semua Status</option>
                <option value="success">Sukses</option>
                <option value="duplicate">Duplikat</option>
                <option value="unknown">Tidak Dikenal</option>
            </select>
        </div>
    </div>

    <!-- Mobile View: Card Based -->
    <div class="md:hidden space-y-3">
        @forelse($logs as $log)
        <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200 flex flex-col gap-2">
            <div class="flex justify-between items-start">
                <div>
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block mb-1">No. Resi</span>
                    <span class="text-lg font-black text-slate-800">{{ $log->scanned_tracking_number }}</span>
                </div>
                <span class="px-2 py-1 rounded-full text-[10px] font-bold text-white {{ $log->status === 'success' ? 'bg-green-500' : ($log->status === 'duplicate' ? 'bg-rose-500' : 'bg-amber-500') }}">
                    {{ strtoupper($log->status) }}
                </span>
            </div>
            
            <div class="mt-2 text-sm text-slate-600 leading-tight">
                <span class="font-semibold text-slate-700">Barang:</span> {{ $log->receipt ? ($log->receipt->product_name ?: '-') : '-' }}
            </div>
            
            <div class="grid grid-cols-2 gap-2 mt-2 pt-2 border-t border-slate-100">
                <div>
                    <span class="text-[10px] font-semibold text-slate-400 uppercase block">Ekspedisi</span>
                    <span class="text-sm font-medium text-slate-700">{{ $log->receipt ? ($log->receipt->expedition ? $log->receipt->expedition->name : '-') : '-' }}</span>
                </div>
                <div>
                    <span class="text-[10px] font-semibold text-slate-400 uppercase block">Operator</span>
                    <span class="text-sm font-medium text-slate-700">{{ $log->user->name ?? 'System' }}</span>
                </div>
            </div>
            
            <div class="mt-1 pt-2 border-t border-slate-100">
                <span class="text-[10px] font-semibold text-slate-400 uppercase block">Waktu Scan</span>
                <span class="text-xs font-medium text-slate-500">{{ $log->created_at->format('d M Y, H:i:s') }}</span>
            </div>
        </div>
        @empty
        <div class="bg-white p-8 rounded-xl shadow-sm border border-slate-200 text-center text-slate-500">
            Data tidak ditemukan.
        </div>
        @endforelse
    </div>

    <!-- Desktop View: Table -->
    <div class="hidden md:block bg-white shadow rounded-xl overflow-hidden">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th class="px-6 py-3">Waktu</th>
                    <th class="px-6 py-3">No. Resi</th>
                    <th class="px-6 py-3">Barang</th>
                    <th class="px-6 py-3">Ekspedisi</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Operator</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr class="bg-white border-b hover:bg-gray-50">
                    <td class="px-6 py-4">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                    <td class="px-6 py-4 font-medium text-gray-900">{{ $log->scanned_tracking_number }}</td>
                    <td class="px-6 py-4 truncate max-w-[200px]" title="{{ $log->receipt ? $log->receipt->product_name : '' }}">{{ $log->receipt ? ($log->receipt->product_name ?: '-') : '-' }}</td>
                    <td class="px-6 py-4">{{ $log->receipt ? ($log->receipt->expedition ? $log->receipt->expedition->name : '-') : '-' }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded-full text-xs text-white {{ $log->status === 'success' ? 'bg-green-500' : ($log->status === 'duplicate' ? 'bg-red-500' : 'bg-yellow-500') }}">
                            {{ strtoupper($log->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">{{ $log->user->name ?? 'System' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center">Data tidak ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        @if($logs->hasPages())
        <div class="text-xs text-slate-500 mb-3 md:hidden text-center font-semibold">
            Halaman {{ $logs->currentPage() }} dari {{ $logs->lastPage() }}
        </div>
        @endif
        {{ $logs->links() }}
    </div>
</div>
