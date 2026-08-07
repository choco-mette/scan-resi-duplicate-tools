## 1. Ringkasan Proyek

Sistem aplikasi hibrida (Web & Mobile) yang dirancang untuk memvalidasi nomor resi yang dicetak melalui pemindai, mencatat riwayat pemindaian, dan bertindak sebagai *gatekeeper* untuk mencegah terjadinya pengiriman ganda akibat duplikasi pencetakan resi. Sistem ini berfokus pada kecepatan pemindaian di lapangan melalui perangkat *mobile*, dengan pengelolaan data terpusat via dasbor web.

## 2. Latar Belakang & Masalah

Saat proses *packing* massal, staf sering kali secara tidak sengaja mencetak atau memproses resi yang sama lebih dari satu kali. Hal ini memicu kerugian berupa pengiriman ganda, selisih stok, dan kebingungan ekspedisi. Dibutuhkan sistem pemindai yang dapat langsung memberikan peringatan keras (visual, audio, dan getaran) jika sebuah resi sudah pernah di-*scan*.

## 3. Target Pengguna & Platform

Sistem ini membagi peran pengguna ke dalam dua *platform* berbeda agar antarmuka lebih fokus:

1. **Admin / Supervisor (Platform Web Desktop & Mobile ):** Bertugas mengimpor data target resi harian, memantau metrik secara detail, dan mengunduh laporan Excel. Bertugas memindai resi menggunakan kamera *smartphone* atau *Bluetooth Scanner* yang terhubung ke HP.

## 4. Ruang Lingkup & Fitur Utama

### 4.1. Modul Import & Export (Fokus Admin - Web)

- **Import Data (Awal Shift):** Upload file berformat `.xlsx` atau `.csv` yang berisi daftar resi valid hari itu. Sistem otomatis memetakan kolom (Nomor Resi, Ekspedisi, dll) dan mencegah data ganda masuk ke sistem master.
- **Export Data (Akhir Shift):** Mengunduh *log* riwayat pemindaian ke dalam format `.xlsx` untuk rekonsiliasi.

### 4.2. Modul Pemindai / Scanner (Fokus Operator - Mobile)

- **Input Fleksibel:** Mendukung pemindaian langsung via kamera belakang *smartphone* (*Viewfinder* dilengkapi tombol senter/flash) maupun input dari *Bluetooth Scanner Gun*.
- **Kiosk Mode (Fokus Layar Penuh):** Saat mode *Scan* aktif, elemen navigasi lain disembunyikan agar operator tidak salah sentuh. Tombol yang ada dibuat berukuran ekstra besar (*Large Touch Targets*).
- **Deteksi Duplikasi & Multi-Sensor Feedback:**
    - **Resi Valid & Baru:** Layar hijau, terdengar suara *beep* pendek, dan HP bergetar pendek (*haptic feedback*). Status berubah menjadi *Scanned*.
    - **Resi Duplikat (Pernah di-scan):** Layar berkedip **merah penuh**, terdengar suara alarm/buzzer panjang, dan HP bergetar panjang.
    - **Resi Tidak Dikenal:** Layar kuning, notifikasi bahwa resi tidak ada di daftar *import* hari ini.

### 4.3. Modul Dashboard

- **Versi Web (Admin):** Menampilkan metrik lengkap, grafik batang/lingkaran per ekspedisi, *Total Target*, *Selesai*, *Sisa*, dan *Total Insiden Duplikat*.
- **Versi Mobile (Glanceable UI):** Menampilkan angka dengan ukuran *font* sangat besar agar mudah dibaca sekilas. Hanya fokus pada metrik utama: **[Sisa Target Hari Ini]** dan **[Total Selesai]**.

### 4.4. Modul Riwayat (History)

- **Versi Web:** Tabel data konvensional dengan fitur filter (Berdasarkan tanggal, ekspedisi, status) dan tombol Export Excel.
- **Versi Mobile (Card-Based Layout):** Menampilkan daftar *scan* terakhir dalam bentuk kartu (bukan tabel yang menyempit). Setiap kartu menampilkan Nomor Resi tebal, logo ekspedisi, dan warna penanda (Hijau/Merah).

## **5. Alur Pengguna (User Flow)**

| **Langkah** | **Aktor** | **Platform** | **Aktivitas** | **Respon Sistem** |
| --- | --- | --- | --- | --- |
| 1 | Admin | Web | Mengunggah file Excel berisi target resi harian. | Sistem memproses antrean resi dengan status *Unscanned*. |
| 2 | Admin | Web  | Membuka aplikasi, memilih Ekspedisi, dan mengarahkan kamera/scanner ke paket. | Sistem memfokuskan pembacaan *barcode* atau menerima input teks otomatis. |
| 3 | Sistem | Backend | Memvalidasi kecocokan nomor resi di *database* dalam waktu < 1 detik. | Memberikan respons sensorik ke HP operator (Hijau/Beep untuk sukses, Merah/Alarm untuk Duplikat). |
| 4 | Admin | Web | Memantau pergerakan data dari dasbor secara *real-time*. | Angka *Total Selesai* terus bertambah. |
| 5 | Admin | Web | Melakukan *Export* Excel di akhir hari operasional. | Menghasilkan laporan lengkap termasuk daftar paket yang terindikasi duplikat. |

## 6. Kriteria Penerimaan (Acceptance Criteria)

- **Kecepatan Respons:** Validasi pemindaian dari HP ke *server* dan kembali ke layar tidak boleh lebih dari **1 detik** agar tidak menghambat ritme kerja.
- **Navigasi Mobile:** Aplikasi *mobile* harus menggunakan *Bottom Navigation Bar* agar perpindahan antar layar (Scan, Dashboard, History) mudah dijangkau satu jari.
- **Toleransi Jaringan (Offline Cache):** Jika koneksi WiFi/Seluler di gudang tidak stabil, aplikasi *mobile* harus mampu menyimpan data *scan* selama beberapa detik dan melakukan sinkronisasi otomatis saat jaringan kembali.
- **Pemetaan Excel:** Sistem web harus kebal terhadap *error* jika terdapat baris kosong atau format sel yang tidak beraturan pada file Excel yang diunggah.

## 7. Spesifikasi & Rekomendasi Teknis (Tech Stack)

- **Framework Utama:** **Laravel** (Direkomendasikan menggunakan Laravel versi terbaru). Berfungsi menangani otentikasi, *routing*, logika bisnis, dan penyajian antarmuka.
- **Database:** **MySQL**. Handal untuk menyimpan relasi data antara antrean resi, log pemindaian, dan pengguna (operator/admin).
- **Ekosistem Frontend Laravel:**
    - Bisa menggunakan **Laravel Livewire** dipadukan dengan Alpine.js untuk membuat antarmuka *scanner* yang dinamis, cepat, dan reaktif tanpa *reload* halaman (SPA-like feel).
    - *Styling* direkomendasikan menggunakan **Tailwind CSS** agar UI khusus *mobile* lebih mudah dibangun dan disesuaikan secara komponen.
- **Pustaka Tambahan (Packages):**
    - `maatwebsite/excel` (Laravel Excel): Untuk menangani fitur *Import* target resi (membaca antrean) dan *Export* laporan riwayat.
    - `html5-qrcode` (JavaScript): Jika ingin memanfaatkan kamera *smartphone* operator sebagai *scanner* langsung melalui *browser*.
- **Pendekatan PWA (Progressive Web App):** Sistem dapat diatur sebagai PWA sehingga operator di gudang dapat melakukan "Add to Home Screen" di *smartphone* mereka. Aplikasi akan terasa seperti aplikasi *native* dengan layar penuh tanpa *address bar browser* yang mengganggu.

## ERD

```bash
erDiagram
    USERS ||--o{ SCAN_LOGS : "melakukan scan"
    EXPEDITIONS ||--o{ RECEIPTS : "memiliki"
    RECEIPTS ||--o{ SCAN_LOGS : "memiliki riwayat"

    USERS {
        bigint id PK
        string name
        string email UK
        string password
        enum role "admin, packer"
        timestamp created_at
        timestamp updated_at
    }

    EXPEDITIONS {
        bigint id PK
        string name
        string code UK
        timestamp created_at
        timestamp updated_at
    }

    RECEIPTS {
        bigint id PK
        bigint expedition_id FK
        string tracking_number "Indexed"
        string order_id "Nullable"
        enum status "unscanned, scanned"
        timestamp scanned_at "Nullable"
        timestamp created_at
        timestamp updated_at
    }

    SCAN_LOGS {
        bigint id PK
        bigint user_id FK
        bigint receipt_id FK "Nullable"
        string scanned_tracking_number
        enum status "success, duplicate, unknown"
        timestamp created_at
        timestamp updated_at
    }
```