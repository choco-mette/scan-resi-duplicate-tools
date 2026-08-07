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
    <div class="md:hidden space-y-4">
        @forelse($logs as $log)
        <div class="bg-white rounded-xl shadow p-4 border-l-4 {{ $log->status === 'success' ? 'border-green-500' : ($log->status === 'duplicate' ? 'border-red-500' : 'border-yellow-500') }}">
            <div class="flex justify-between items-start mb-2">
                <div class="font-bold text-lg text-gray-800">{{ $log->scanned_tracking_number }}</div>
                <div class="text-xs px-2 py-1 rounded-full text-white {{ $log->status === 'success' ? 'bg-green-500' : ($log->status === 'duplicate' ? 'bg-red-500' : 'bg-yellow-500') }}">
                    {{ strtoupper($log->status) }}
                </div>
            </div>
            <div class="text-sm text-gray-500 flex justify-between">
                <span>{{ $log->receipt ? ($log->receipt->expedition ? $log->receipt->expedition->name : 'N/A') : 'Tidak terdaftar' }}</span>
                <span>{{ $log->created_at->format('H:i:s') }}</span>
            </div>
            <div class="text-xs text-gray-400 mt-2">
                Oleh: {{ $log->user->name ?? 'System' }}
            </div>
        </div>
        @empty
        <div class="bg-white p-6 rounded-xl shadow text-center text-gray-500">
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
                    <td colspan="5" class="px-6 py-8 text-center">Data tidak ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $logs->links() }}
    </div>
</div>
