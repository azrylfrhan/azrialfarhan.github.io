# Sistem Informasi Kelurahan (SI KAMJA)
![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white) ![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white) ![Bootstrap](https://img.shields.io/badge/Bootstrap-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white)

Sebuah aplikasi web komprehensif yang dirancang untuk mendigitalisasi layanan administrasi di tingkat kelurahan. Proyek ini dikembangkan sebagai bagian dari tugas akhir (skripsi) dengan fokus pada efisiensi layanan publik dan penerapan teknologi cerdas.

---

## 📜 Deskripsi Proyek

SI KAMJA (Sistem Informasi Kelurahan Kampung Jawa) bertujuan untuk mentransformasi proses administrasi manual menjadi alur kerja digital yang efisien, modern, dan mudah diakses. Aplikasi ini menjembatani antara warga dan aparat kelurahan, mengurangi waktu tunggu, dan meningkatkan transparansi layanan.

Sebagai inti dari penelitian skripsi, proyek ini juga akan mengimplementasikan model **Machine Learning** untuk menciptakan sistem *triage* cerdas, yang secara otomatis memprioritaskan permohonan surat yang masuk untuk meningkatkan produktivitas staf administrasi.

---

## ✨ Fitur Utama

### A. Fitur untuk Publik / Warga
- **Layanan Pengurusan Surat Online:** Formulir pengajuan surat dengan validasi NIK otomatis.
- **Layanan Pengaduan Warga:** Formulir untuk mengirim keluhan atau laporan lengkap dengan fitur unggah foto bukti.
- **Lacak Status:** Halaman untuk melacak progres permohonan atau pengaduan menggunakan kode unik.
- **Notifikasi WhatsApp:** Sistem notifikasi otomatis ke nomor WhatsApp warga untuk setiap pembaruan status.

### B. Fitur untuk Panel Admin
- **Dashboard Interaktif:** Menampilkan ringkasan statistik dan visualisasi data (grafik tren, komposisi penduduk).
- **Manajemen Data Penduduk:** Fungsi CRUD penuh dengan fitur pencarian, impor, dan ekspor data via Excel.
- **Manajemen Jenis Surat:** CRUD untuk jenis surat, lengkap dengan kemampuan unggah template `.docx` dan definisi persyaratan.
- **Manajemen Permohonan & Pengaduan:** Tampilan terpusat untuk memverifikasi, menanggapi, dan mengubah status semua data yang masuk.
- **Generasi Surat Otomatis:** Kemampuan untuk membuat dokumen `.docx` secara instan dari template.
- **Arsip Digital:** Semua surat yang telah selesai diproses diarsipkan secara otomatis dan dapat diunduh kembali.
- **Notifikasi Internal:** Ikon lonceng di *navbar* yang memberikan notifikasi *real-time* kepada admin jika ada data baru masuk.
- **Manajemen Pengguna:** CRUD untuk mengelola akun admin dan kepala lingkungan beserta hak aksesnya.
- **Sistem Alert:** Notifikasi sukses dan error yang konsisten di semua fitur untuk memberikan umpan balik visual.

### C. Fitur Inti Skripsi (Dalam Pengembangan)
- **Sistem Triage Cerdas:** Menggunakan model *Machine Learning* untuk secara otomatis mengklasifikasikan permohonan surat baru ke dalam tingkat prioritas: **Jalur Cepat**, **Jalur Normal**, atau **Perlu Verifikasi**.

---

## 🛠️ Teknologi yang Digunakan

* **Backend:** PHP 8.x, Laravel 11.x
* **Frontend:** HTML5, CSS3, JavaScript, Bootstrap (SB Admin 2)
* **Database:** MySQL
* **Pustaka Utama:**
  * `maatwebsite/excel`: Untuk fitur Impor/Ekspor data Excel.
  * `phpoffice/phpword`: Untuk generasi dokumen `.docx`.
  * `chart.js`: Untuk visualisasi data di dashboard.
* **Pengembangan:** Visual Studio Code, Git & GitHub, Composer

---

## 🚀 Cara Menjalankan Proyek Secara Lokal

1. **Clone repositori ini:**
   ```bash
   git clone [https://github.com/azrylfrhan/proyek-skripsi-sisforkamja.git](https://github.com/azrylfrhan/proyek-skripsi-sisforkamja.git)
   cd proyek-skripsi-sisforkamja
   ```

2. **Instal dependensi PHP:**
   ```bash
   composer install
   ```

3. **Salin file `.env`:**
   ```bash
   copy .env.example .env
   ```

4. **Buat kunci aplikasi:**
   ```bash
   php artisan key:generate
   ```

5. **Konfigurasi database Anda** di dalam file `.env` (atur `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).

6. **Jalankan migrasi database:**
   ```bash
   php artisan migrate --seed
   ```
   *(Gunakan `--seed` jika Anda memiliki seeder untuk membuat data awal)*

7. **Buat symbolic link untuk storage:**
   ```bash
   php artisan storage:link
   ```

8. **Jalankan server pengembangan:**
   ```bash
   php artisan serve
   ```
   Aplikasi akan berjalan di `http://127.0.0.1:8000`.

---

## 👨‍💻 Penulis

* **Nama:** Azrial Fahri Farhan
* **NIM:** 21024160
* **Program Studi:** Teknik Informatika

Proyek ini dibuat sebagai bagian dari pemenuhan syarat kelulusan program Sarjana.
