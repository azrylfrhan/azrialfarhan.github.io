<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use App\Models\penduduk;
use App\Traits\SendsWhatsApp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- Menggunakan facade Auth secara eksplisit
use Illuminate\Support\Str;

class PengaduanController extends Controller
{
    use SendsWhatsApp;

    /**
     * Menampilkan daftar pengaduan di panel admin/kepling.
     */
    public function index(Request $request)
    {
        // 1. Mulai query builder untuk model Pengaduan
        $query = Pengaduan::query();

        // 2. Terapkan filter berdasarkan Kategori jika ada input
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // 3. Terapkan filter berdasarkan Status jika ada input
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 4. Terapkan pencarian berdasarkan keyword jika ada input
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            
            // Mencari di beberapa kolom: 'kode_pengaduan' atau di nama 'penduduk' yang berelasi
            $query->where(function ($subQuery) use ($searchTerm) {
                $subQuery->where('kode_pengaduan', 'like', "%{$searchTerm}%")
                        ->orWhereHas('penduduk', function ($relationQuery) use ($searchTerm) {
                            $relationQuery->where('nama', 'like', "%{$searchTerm}%");
                        });
            });
        }

        // 5. Ambil data dengan relasi 'penduduk' (untuk performa) dan urutkan dari yang terbaru
        //    Lalu, paginasi hasilnya.
        $pengaduans = $query->with('penduduk')
                            ->latest() // Mengurutkan dari yang paling baru
                            ->paginate(10) // Menampilkan 10 data per halaman
                            ->appends($request->query()); // Agar filter tetap aktif saat pindah halaman

        // 6. Kirim data yang sudah difilter ke view
        return view('pages.pengaduan.index', compact('pengaduans'));
    }

    /**
     * Menampilkan form pengaduan untuk publik.
     */
    public function create()
    {
        return view('pages.pengaduan.create');
    }

    /**
     * Menyimpan pengaduan baru dari warga.
     */
    public function store(Request $request)
    {
        $request->validate([
            'penduduk_id' => 'required|exists:penduduks,id', // Validasi dari NIK-autofill
            'no_telepon' => 'required|string|max:20',        // Validasi untuk nomor telepon manual
            'kategori' => 'required|in:Infrastruktur,Kebersihan,Keamanan,Layanan Publik,Lainnya',
            'judul' => 'required|string|max:255',
            'isi_laporan' => 'required|string',
            'foto_bukti' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $penduduk = Penduduk::findOrFail($request->penduduk_id);
        
        // ... (Logika pembuatan Kode Pengaduan tetap sama)
        $kodePengaduan = 'ADU-' . now()->format('ymd') . '-' . strtoupper(Str::random(4));
        
        $pathFoto = null;
        if ($request->hasFile('foto_bukti')) {
            $pathFoto = $request->file('foto_bukti')->store('pengaduan_bukti', 'public');
        }

        $pengaduan = Pengaduan::create([
            'kode_pengaduan' => $kodePengaduan,
            'penduduk_id' => $penduduk->id,
            'no_telepon' => $request->no_telepon, // <-- Mengambil nomor dari input form
            'kategori' => $request->kategori,
            'judul' => $request->judul,
            'isi_laporan' => $request->isi_laporan,
            'foto_bukti' => $pathFoto,
        ]);

        // Kirim notifikasi menggunakan nomor telepon yang baru diinput
        if ($pengaduan->no_telepon) {
            $pesan = "[Laporan Diterima] Halo *{$penduduk->nama}*. Laporan Anda \"{$pengaduan->judul}\" telah kami terima dengan kode pelacakan: *{$kodePengaduan}*. Terima kasih.";
            $this->sendWhatsAppNotification($pengaduan->no_telepon, $pesan);
        }

        try {
        $admins = \App\Models\User::whereHas('role', function ($query) {
            $query->where('name', 'Admin'); // Ganti 'nama_role' dengan nama kolom di tabel 'roles' Anda
        })->get();

        // Kirim notifikasi ke semua admin yang ditemukan
        if ($admins->isNotEmpty()) {
            \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\NewEntryNotification($pengaduan, 'pengaduan'));
        }

    } catch (\Exception $e) {
        // Abaikan jika gagal
    }

        return redirect('/administrasi')->with('success', 'Pengaduan berhasil dikirim! Kode pelacakan telah dikirim ke WhatsApp Anda.');
    }

    /**
     * Memproses tanggapan dari admin.
     */
    public function update(Request $request, Pengaduan $pengaduan)
    {
        // Membungkus seluruh logika di dalam blok try-catch
        try {
            // Validasi input (logika Anda tetap sama)
            $request->validate([
                'status' => 'required|in:Dalam Peninjauan,Ditindaklanjuti,Selesai,Ditolak',
                'tanggapan_admin' => 'required|string',
            ]);

            // Memuat relasi penduduk untuk mengambil data nama & no telepon
            $pengaduan->load('penduduk');

            // Memperbarui data pengaduan (logika Anda tetap sama)
            $pengaduan->update([
                'status' => $request->status,
                'tanggapan_admin' => $request->tanggapan_admin,
                'user_id' => Auth::id(),
                'tanggal_tanggapan' => now(),
            ]);

            // Mengirim notifikasi WhatsApp ke warga (logika Anda tetap sama)
            if ($pengaduan->no_telepon) { // Menggunakan no_telepon dari pengaduan
                $namaPemohon = $pengaduan->penduduk->nama;
                $header = "[Update Status Pengaduan]\n\n";
                $footer = "\n\nTerima kasih,\nPemerintah Kelurahan Kampung Jawa";
                $pesan = $header . "Halo *{$namaPemohon}*,\n\nAda pembaruan untuk laporan Anda *{$pengaduan->kode_pengaduan}*.\n\nStatus Baru: *{$pengaduan->status}*\nTanggapan: *{$pengaduan->tanggapan_admin}*" . $footer;
                
                $this->sendWhatsAppNotification($pengaduan->no_telepon, $pesan);
            }

            // Jika semua langkah di atas berhasil, kirim pesan 'success'
            return redirect()->back()->with('success', 'Status pengaduan berhasil diubah dan notifikasi telah dikirim.');

        } catch (\Exception $e) {
            // Jika terjadi error apa pun di dalam blok try, tangkap errornya.
            // Anda bisa mencatat errornya untuk debugging di masa depan jika perlu:
            // \Log::error('Error saat update pengaduan: ' . $e->getMessage());
            
            // Kirim pesan 'error' kembali ke halaman sebelumnya.
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui data. Silakan coba lagi.');
        }
    }

    
}
