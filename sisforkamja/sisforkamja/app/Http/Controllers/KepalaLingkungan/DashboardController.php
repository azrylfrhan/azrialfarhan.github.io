<?php

namespace App\Http\Controllers\KepalaLingkungan;

use App\Http\Controllers\Controller;
use App\Models\penduduk;
use App\Models\Pengaduan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $lingkunganUser = $user->lingkungan;

        // --- DATA PENDUDUK UNTUK KARTU & GRAFIK ---
        $pendudukQuery = Penduduk::where('lingkungan', $lingkunganUser)->where('status', 'aktif');

        // Kartu Statistik (Tidak berubah)
        $jumlahPenduduk = $pendudukQuery->clone()->count();
        $jumlahPria = $pendudukQuery->clone()->whereRaw('LOWER(jenis_kelamin) = ?', ['pria'])->count();
        $jumlahWanita = $pendudukQuery->clone()->whereRaw('LOWER(jenis_kelamin) = ?', ['wanita'])->count();

        // --- PERBAIKAN LOGIKA GRAFIK USIA DI SINI ---
        // 1. Ambil koleksi data usia dari semua penduduk
        $usiaData = $pendudukQuery->clone()->get()->map(function ($penduduk) {
            return Carbon::parse($penduduk->tanggal_lahir)->age;
        });

        // 2. Gunakan metode filter() yang benar untuk menghitung setiap kelompok
        $kelompokUsia = [
            'Anak (0-12)' => $usiaData->filter(function ($age) { return $age >= 0 && $age <= 12; })->count(),
            'Remaja (13-21)' => $usiaData->filter(function ($age) { return $age >= 13 && $age <= 21; })->count(),
            'Dewasa (22-55)' => $usiaData->filter(function ($age) { return $age >= 22 && $age <= 55; })->count(),
            'Lansia (>55)' => $usiaData->filter(function ($age) { return $age > 55; })->count(),
        ];
        
        $usiaLabels = array_keys($kelompokUsia);
        $usiaChartData = array_values($kelompokUsia);
        // --- AKHIR PERBAIKAN ---

        // --- DATA PENGADUAN TERBARU (Tidak berubah) ---
        $pengaduanLingkungan = Pengaduan::with('penduduk')
            ->whereHas('penduduk', function ($query) use ($lingkunganUser) {
                $query->where('lingkungan', $lingkunganUser);
            })
            ->where('status', '!=', 'Selesai')
            ->latest()
            ->take(5)
            ->get();

        // Kirim semua data ke view
        return view('pages.dashboard_kepala_lingkungan', compact(
            'jumlahPenduduk',
            'jumlahPria',
            'jumlahWanita',
            'usiaLabels',
            'usiaChartData',
            'pengaduanLingkungan'
        ));
    }
}
