<div class="p-4 md:p-6 max-w-7xl mx-auto" x-data="{ showModal: @entangle('showModal') }">
    <div class="mb-6 flex flex-col md:flex-row justify-between md:items-end gap-4">
        <div>
            <h2 class="text-3xl font-bold text-slate-800">Master Data Resi</h2>
            <p class="text-slate-500">Kelola resi yang akan discan hari ini</p>
        </div>
        
        <div class="flex flex-col md:flex-row gap-3">
            
            <!-- Import Excel Trigger -->
            <form wire:submit="import" class="flex gap-2">
                <input wire:model="file" type="file" accept=".xlsx,.csv,.xls" id="excel_upload" class="hidden" onchange="this.form.dispatchEvent(new Event('submit'))">
                <button type="button" onclick="document.getElementById('excel_upload').click()" class="flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition shadow-sm" wire:loading.attr="disabled">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    <span wire:loading.remove wire:target="file">Import Excel</span>
                    <span wire:loading wire:target="file">Uploading...</span>
                </button>
            </form>
            
            <!-- Tambah Manual Trigger -->
            <button @click="showModal = true" class="flex items-center gap-2 bg-sky-600 hover:bg-sky-700 text-white px-4 py-2 rounded-lg font-medium transition shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
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

    <!-- Data Table -->
    <div class="bg-white shadow rounded-xl overflow-hidden border border-slate-200">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-slate-500">
                <thead class="text-xs text-slate-700 uppercase bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">No. Resi</th>
                        <th class="px-6 py-4">No. Pesanan</th>
                        <th class="px-6 py-4">Ekspedisi</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Waktu Dibuat</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($receipts as $receipt)
                    <tr class="bg-white border-b hover:bg-slate-50">
                        <td class="px-6 py-4 font-bold text-slate-900">{{ $receipt->tracking_number }}</td>
                        <td class="px-6 py-4">{{ $receipt->order_id ?: '-' }}</td>
                        <td class="px-6 py-4">{{ $receipt->expedition ? $receipt->expedition->name : '-' }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-full text-xs text-white {{ $receipt->status === 'scanned' ? 'bg-green-500' : 'bg-slate-400' }}">
                                {{ strtoupper($receipt->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-400">{{ $receipt->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-500">Belum ada data resi. Silakan import atau tambah manual.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($receipts->hasPages())
        <div class="px-6 py-4 border-t border-slate-200 bg-slate-50">
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
</div>
