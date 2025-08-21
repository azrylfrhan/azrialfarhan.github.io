<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use App\Models\PermohonanSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LacakController extends Controller
{
    /**
     * Menampilkan halaman lacak dan memproses pencarian untuk semua jenis layanan.
     */
    public function lacak(Request $request)
    {
        $hasil = null;
        $jenisLayanan = null;
        $errorMessage = null;

        // Cek apakah ada input 'kode' di URL
        if ($request->has('kode') && !empty($request->kode)) {
            $kode = $request->kode;

            // Logika untuk mendeteksi jenis layanan berdasarkan awalan kode
            if (Str::startsWith($kode, 'ADU-')) {
                // Ini adalah pengaduan
                $hasil = Pengaduan::where('kode_pengaduan', $kode)->with('penduduk')->first();
                $jenisLayanan = 'pengaduan';
            } else {
                // Asumsikan sisanya adalah permohonan surat
                $hasil = PermohonanSurat::where('kode_pelacakan', $kode)->with('penduduk', 'jenisSurat')->first();
                $jenisLayanan = 'surat';
            }
            
            // Jika setelah dicari tetap tidak ada, siapkan pesan error
            if (!$hasil) {
                $errorMessage = 'Kode Pelacakan tidak ditemukan atau salah. Mohon periksa kembali.';
                $jenisLayanan = null; // Reset jenis layanan
            }
        }

        // Tampilkan view dengan membawa semua variabel yang dibutuhkan
        return view('pages.administrasi.lacak', compact('hasil', 'jenisLayanan', 'errorMessage'));
    }
}
