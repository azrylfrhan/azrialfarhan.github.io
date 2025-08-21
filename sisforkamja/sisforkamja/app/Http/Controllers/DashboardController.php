<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JenisSurat;
use App\Models\penduduk;
use App\Models\PermohonanSurat;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB; // <-- TAMBAHKAN INI

class DashboardController extends Controller
{
    public function index()
    {
        // Data untuk Kartu Statistik (Tidak ada perubahan)
        $jumlahPermohonanBaru = PermohonanSurat::where('status', 'Menunggu')->count();
        $jumlahPendudukAktif = Penduduk::where('status', 'aktif')->count();
        $suratSelesaiBulanIni = PermohonanSurat::where('status', 'Selesai')
                                            ->whereMonth('tanggal_selesai', now()->month)
                                            ->count();
        $jumlahJenisSurat = JenisSurat::count();

        // ===================================================================
        // === BAGIAN YANG DIGANTI: Mengambil data untuk Prioritas Kerja ===
        // ===================================================================
        // 1. Buat query dasar untuk semua permohonan yang masih aktif
        $queryTugas = PermohonanSurat::with('penduduk', 'jenisSurat')
                        ->whereIn('status', ['Menunggu', 'Diproses']) // Ambil yang statusnya Menunggu atau Diproses
                        ->latest(); // Urutkan dari yang paling baru

        // 2. Kelompokkan berdasarkan prioritas, ambil 5 teratas untuk masing-masing
        $tugasTinggi = $queryTugas->clone()->where('prioritas', 'Tinggi')->take(5)->get();
        $tugasSedang = $queryTugas->clone()->where('prioritas', 'Sedang')->take(5)->get();
        $tugasRendah = $queryTugas->clone()->where('prioritas', 'Rendah')->take(5)->get();
        // ===================================================================

        // Data untuk Grafik Tren Permohonan (Tidak ada perubahan)
        $chartLabels = [];
        $chartData = [];
        $tahunIni = now()->year;
        $jumlahHariBulanIni = now()->daysInMonth;
        for ($hari = 1; $hari <= $jumlahHariBulanIni; $hari++) {
            $chartLabels[] = Carbon::create(null, now()->month, $hari)->format('d');
            $jumlah = PermohonanSurat::whereYear('tanggal_permohonan', $tahunIni)
                                        ->whereMonth('tanggal_permohonan', now()->month)
                                        ->whereDay('tanggal_permohonan', $hari)
                                        ->count();
            $chartData[] = $jumlah;
        }

        // Data untuk Grafik Komposisi Penduduk (Tidak ada perubahan)
        $jumlahPria = Penduduk::where('jenis_kelamin', 'pria')->where('status', 'aktif')->count();
        $jumlahWanita = Penduduk::where('jenis_kelamin', 'wanita')->where('status', 'aktif')->count();

        // Data untuk Grafik Agama (Tidak ada perubahan)
        $dataAgama = Penduduk::where('status', 'aktif')
            ->select('agama', DB::raw('count(*) as total'))
            ->groupBy('agama')
            ->orderBy('total', 'desc')
            ->get();
        $agamaLabels = $dataAgama->pluck('agama');
        $agamaData = $dataAgama->pluck('total');

        // Kirim semua data ke view
        return view('pages.dashboard', [
            'jumlahPermohonanBaru' => $jumlahPermohonanBaru,
            'jumlahPendudukAktif' => $jumlahPendudukAktif,
            'suratSelesaiBulanIni' => $suratSelesaiBulanIni,
            'jumlahJenisSurat' => $jumlahJenisSurat,
            'chartLabels' => $chartLabels,
            'chartData' => $chartData,
            'jumlahPria' => $jumlahPria,
            'jumlahWanita' => $jumlahWanita,
            'agamaLabels' => $agamaLabels,
            'agamaData' => $agamaData,
            // --- Variabel baru untuk dikirim ke view ---
            'tugasTinggi' => $tugasTinggi,
            'tugasSedang' => $tugasSedang,
            'tugasRendah' => $tugasRendah,
        ]);
    }
}
