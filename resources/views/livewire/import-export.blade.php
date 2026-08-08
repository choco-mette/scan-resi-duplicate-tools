<div class="p-4 md:p-6 max-w-7xl mx-auto" x-data="{ showModal: @entangle('showModal') }">
    <div class="mb-6 flex flex-col md:flex-row justify-between md:items-end gap-4">
        <div>
            <h2 class="text-3xl font-bold text-slate-800">Master Data Resi</h2>
            <p class="text-slate-500">Kelola resi yang akan discan hari ini</p>
        </div>
        
        <div class="flex flex-row gap-2 md:gap-3 w-full md:w-auto mt-4 md:mt-0">
            
            <!-- Import Excel Trigger -->
            <div class="flex-1 flex flex-col">
                <input wire:model="file" type="file" accept=".xlsx,.csv,.xls" id="excel_upload" class="hidden">
                <button type="button" onclick="document.getElementById('excel_upload').click()" class="w-full flex justify-center items-center gap-1 md:gap-2 bg-green-600 hover:bg-green-700 text-white px-2 py-2 md:px-4 md:py-2 rounded-lg font-medium transition shadow-sm text-xs md:text-base" wire:loading.attr="disabled">
                    <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    <span wire:loading.remove wire:target="file">Import Excel</span>
                    <span wire:loading wire:target="file">Uploading...</span>
                </button>
                @error('file') <span class="text-red-500 text-[10px] font-bold mt-1 text-center">{{ $message }}</span> @enderror
            </div>
            
            <!-- Tambah Manual Trigger -->
            <button @click="showModal = true" class="flex-1 flex justify-center items-center gap-1 md:gap-2 bg-sky-600 hover:bg-sky-700 text-white px-2 py-2 md:px-4 md:py-2 rounded-lg font-medium transition shadow-sm text-xs md:text-base">
                <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Manual
            </button>
        </div>
    </div>

    @if ($importStatus)
        <div class="p-4 mb-4 text-sm rounded-lg {{ str_contains($importStatus, 'Sukses') || str_contains($importStatus, 'berhasil') ? 'bg-green-100 text-green-700 border border-green-200' : 'bg-red-100 text-red-700 border border-red-200' }}">
            {{ $importStatus }}
            <button wire:click="$set('importStatus', '')" class="float-right text-lg leading-none font-bold">&times;</button>
        </div>
    @endif

    <!-- Data List (Responsive) -->
    <div class="bg-transparent md:bg-white md:shadow rounded-xl overflow-hidden md:border md:border-slate-200">
        
        <!-- Desktop Table View -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-sm text-left text-slate-500">
                <thead class="text-xs text-slate-700 uppercase bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">No. Resi</th>
                        <th class="px-6 py-4">Batas Kirim</th>
                        <th class="px-6 py-4">Barang</th>
                        <th class="px-6 py-4">No. Pesanan</th>
                        <th class="px-6 py-4">Ekspedisi</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Waktu Dibuat</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($receipts as $receipt)
                    <tr class="bg-white border-b hover:bg-slate-50">
                        <td class="px-6 py-4 font-bold text-slate-900">{{ $receipt->tracking_number }}</td>
                        <td class="px-6 py-4 font-medium {{ $receipt->deadline_at && $receipt->deadline_at->isPast() ? 'text-red-500' : 'text-slate-600' }}">{{ $receipt->deadline_at ? $receipt->deadline_at->format('d/m/Y H:i') : '-' }}</td>
                        <td class="px-6 py-4 truncate max-w-[200px]" title="{{ $receipt->product_name }}">{{ $receipt->product_name ?: '-' }}</td>
                        <td class="px-6 py-4">{{ $receipt->order_id ?: '-' }}</td>
                        <td class="px-6 py-4">{{ $receipt->expedition ? $receipt->expedition->name : '-' }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-full text-xs text-white {{ $receipt->status === 'scanned' ? 'bg-green-500' : 'bg-slate-400' }}">
                                {{ strtoupper($receipt->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-400">{{ $receipt->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-4 text-right">
                            <button onclick="confirmDelete({{ $receipt->id }}, '{{ $receipt->tracking_number }}')" class="text-red-500 hover:text-red-700 transition" title="Hapus Resi">
                                <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-slate-500 bg-white">Belum ada data resi. Silakan import atau tambah manual.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="md:hidden space-y-3 relative">
            @forelse($receipts as $receipt)
            <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200 flex flex-col gap-2 relative">
                <div class="flex justify-between items-start">
                    <div>
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block mb-1">No. Resi</span>
                        <span class="text-lg font-black text-slate-800">{{ $receipt->tracking_number }}</span>
                    </div>
                    <span class="px-2 py-1 rounded-full text-[10px] font-bold text-white {{ $receipt->status === 'scanned' ? 'bg-green-500' : 'bg-slate-400' }}">
                        {{ strtoupper($receipt->status) }}
                    </span>
                </div>
                
                <div class="mt-2 text-sm text-slate-600 leading-tight">
                    <span class="font-semibold text-slate-700">Barang:</span> {{ $receipt->product_name ?: '-' }}
                </div>

                @if($receipt->deadline_at)
                <div class="mt-1 text-sm leading-tight {{ $receipt->deadline_at->isPast() ? 'text-red-500 font-bold' : 'text-slate-600' }}">
                    <span class="font-semibold {{ $receipt->deadline_at->isPast() ? 'text-red-500' : 'text-slate-700' }}">Batas Kirim:</span> {{ $receipt->deadline_at->format('d M Y, H:i') }}
                </div>
                @endif
                
                <div class="grid grid-cols-2 gap-2 mt-2 pt-2 border-t border-slate-100">
                    <div>
                        <span class="text-[10px] font-semibold text-slate-400 uppercase block">Pesanan</span>
                        <span class="text-sm font-medium text-slate-700">{{ $receipt->order_id ?: '-' }}</span>
                    </div>
                    <div>
                        <span class="text-[10px] font-semibold text-slate-400 uppercase block">Ekspedisi</span>
                        <span class="text-sm font-medium text-slate-700">{{ $receipt->expedition ? $receipt->expedition->name : '-' }}</span>
                    </div>
                </div>
                
                <div class="mt-2 pt-3 border-t border-slate-100 flex justify-between items-center">
                    <div>
                        <span class="text-[10px] font-semibold text-slate-400 uppercase block">Ditambahkan pada</span>
                        <span class="text-xs font-medium text-slate-500">{{ $receipt->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    <button onclick="confirmDelete({{ $receipt->id }}, '{{ $receipt->tracking_number }}')" class="flex items-center gap-1.5 px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 rounded-lg text-xs font-bold transition shadow-sm" title="Hapus Resi">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        Hapus
                    </button>
                </div>
            </div>
            @empty
            <div class="bg-white p-8 rounded-xl shadow-sm border border-slate-200 text-center text-slate-500">
                Belum ada data resi. Silakan import atau tambah manual.
            </div>
            @endforelse
        </div>
        
        @if($receipts->hasPages())
        <div class="px-6 py-4 border-t border-slate-200 bg-slate-50">
            <div class="text-xs text-slate-500 mb-3 md:hidden text-center font-semibold">
                Halaman {{ $receipts->currentPage() }} dari {{ $receipts->lastPage() }}
            </div>
            {{ $receipts->links() }}
        </div>
        @endif
    </div>

    <!-- Modal Tambah Manual -->
    <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            
            <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900 bg-opacity-50 transition-opacity" aria-hidden="true" @click="showModal = false"></div>
            
            <!-- This element is to trick the browser into centering the modal contents. -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                
                <form wire:submit.prevent="saveManual">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                <h3 class="text-xl leading-6 font-bold text-slate-900" id="modal-title">Tambah Resi Manual</h3>
                                <div class="mt-4 space-y-4">
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-1">No. Resi *</label>
                                        <input type="text" wire:model="manual_tracking_number" class="w-full border border-slate-300 rounded-lg p-2.5 focus:ring-sky-500 focus:border-sky-500" required>
                                        @error('manual_tracking_number') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-1">No. Pesanan (Opsional)</label>
                                        <input type="text" wire:model="manual_order_id" class="w-full border border-slate-300 rounded-lg p-2.5 focus:ring-sky-500 focus:border-sky-500">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-1">Ekspedisi (Opsional)</label>
                                        <select wire:model="manual_expedition_id" class="w-full border border-slate-300 rounded-lg p-2.5 focus:ring-sky-500 focus:border-sky-500">
                                            <option value="">-- Pilih Ekspedisi --</option>
                                            @foreach($expeditions as $exp)
                                                <option value="{{ $exp->id }}">{{ $exp->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-slate-200">
                        <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-sky-600 text-base font-medium text-white hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Simpan Resi
                        </button>
                        <button type="button" @click="showModal = false" class="mt-3 w-full inline-flex justify-center rounded-lg border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('show-missing-resi-alert', (event) => {
                const orders = event.missingOrders;
                const orderList = orders.slice(0, 5).join(', ') + (orders.length > 5 ? ` dan ${orders.length - 5} lainnya` : '');
                
                Swal.fire({
                    title: 'Resi Kosong Ditemukan!',
                    text: `Ada ${orders.length} pesanan tanpa resi (contoh: ${orderList}). Lanjutkan import data yang ada resinya saja?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#0284c7',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, Lanjutkan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.dispatch('confirm-import');
                    } else {
                        Livewire.dispatch('cancel-import');
                    }
                });
            });
        });

        function confirmDelete(id, trackingNumber) {
            Swal.fire({
                title: 'Hapus Resi?',
                text: `Yakin ingin menghapus resi ${trackingNumber}? Data riwayat scan juga akan terhapus permanen.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('execute-delete-receipt', { id: id });
                }
            });
        }
    </script>
</div>
