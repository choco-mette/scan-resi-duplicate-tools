<div class="h-full flex flex-col" 
    x-data="{
        scanStatus: @entangle('scanStatus'),
        message: @entangle('message'),
        scanTime: @entangle('scanTime'),
        bgColor: 'bg-white',
        isScanning: false,
        html5QrCode: null,
        resetTimeout: null,
        
        init() {
            // Auto-start camera
            setTimeout(() => {
                this.startCamera();
            }, 500);

            this.$watch('scanStatus', value => {
                if(value === 'success') {
                    this.bgColor = 'bg-green-500';
                    this.playSound(800, 'sine', 0.2); // Beep pendek
                    this.vibrate([100]);
                } else if(value === 'duplicate') {
                    this.bgColor = 'bg-red-600 animate-pulse';
                    this.playSound(200, 'sawtooth', 1); // Alarm panjang
                    this.vibrate([500, 200, 500]);
                } else if(value === 'unknown') {
                    this.bgColor = 'bg-yellow-400';
                    this.playSound(400, 'square', 0.5);
                    this.vibrate([300]);
                }
                
                // Reset setelah 4.5 detik, batalkan timer sebelumnya jika ada scan baru
                if (this.resetTimeout) clearTimeout(this.resetTimeout);
                this.resetTimeout = setTimeout(() => { 
                    this.bgColor = 'bg-white';
                    this.scanStatus = null;
                }, 4500);
            });
        },
        playSound(freq, type, duration) {
            try {
                const ctx = new (window.AudioContext || window.webkitAudioContext)();
                const osc = ctx.createOscillator();
                osc.type = type;
                osc.frequency.setValueAtTime(freq, ctx.currentTime);
                osc.connect(ctx.destination);
                osc.start();
                osc.stop(ctx.currentTime + duration);
            } catch(e) {}
        },
        vibrate(pattern) {
            if ('vibrate' in navigator) {
                navigator.vibrate(pattern);
            }
        },
        startCamera() {
            this.isScanning = true;
            this.$dispatch('scanning-started');
            if (!this.html5QrCode) {
                this.html5QrCode = new Html5Qrcode('reader');
            }
            const config = { fps: 10, qrbox: { width: 250, height: 250 } };
            
            this.html5QrCode.start(
                { facingMode: 'environment' }, 
                config, 
                (decodedText) => {
                    @this.processScan(decodedText);
                    this.html5QrCode.pause();
                    setTimeout(() => this.html5QrCode.resume(), 4500);
                },
                (errorMessage) => { }
            ).catch(err => {
                console.log('Gagal mengakses kamera: ' + err);
                this.isScanning = false;
                this.$dispatch('scanning-stopped');
            });
        },
        stopCamera() {
            if (this.html5QrCode) {
                this.html5QrCode.stop().then(() => {
                    this.isScanning = false;
                    this.$dispatch('scanning-stopped');
                });
            }
        }
    }" 
    :class="bgColor">

    <div class="flex-1 flex flex-col items-center justify-center p-6 text-center transition-colors duration-300">
        
        <!-- Status Indicator -->
        <template x-if="scanStatus">
            <div class="mb-8">
                <h2 class="text-4xl font-black text-white mb-2" x-text="scanStatus === 'success' ? 'VALID' : (scanStatus === 'duplicate' ? 'DUPLIKAT' : 'TIDAK DIKENAL')"></h2>
                <p class="text-xl text-white font-semibold mb-2" x-text="message"></p>
                <div class="inline-block px-3 py-1 bg-black/20 rounded-full text-white/90 text-sm font-medium" x-show="scanTime">
                    <svg class="w-4 h-4 inline-block mr-1 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span x-text="scanTime"></span>
                </div>
            </div>
        </template>
        
        <template x-if="!scanStatus">
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-slate-700" x-show="!isScanning">Siap Memindai</h2>
                <h2 class="text-2xl font-bold text-slate-700" x-show="isScanning" style="display: none;">Arahkan ke Barcode</h2>
                <p class="text-slate-500" x-show="!isScanning">Arahkan scanner atau kamera ke resi</p>
            </div>
        </template>

        <!-- HTML5 QRCode Camera Container -->
        <div x-show="isScanning" class="w-full max-w-md mt-4" style="display: none;">
            <div id="reader" class="w-full bg-black rounded-xl overflow-hidden shadow-lg" wire:ignore></div>
            <button @click="stopCamera()" class="mt-4 w-full bg-red-600 hover:bg-red-700 text-white font-bold py-4 rounded-xl large-touch-target transition">TUTUP KAMERA</button>
        </div>

        <button x-show="!isScanning" @click="startCamera()" class="mt-4 w-full bg-slate-800 hover:bg-slate-700 text-white font-bold py-4 rounded-xl shadow-lg large-touch-target flex justify-center items-center gap-2 transition">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            BUKA KAMERA SCANNER
        </button>
        
    </div>

    <!-- Script HTML5 QRCode -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
</div>
