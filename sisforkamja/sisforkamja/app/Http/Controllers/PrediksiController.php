<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; // <-- Gunakan HTTP Client bawaan Laravel

class PrediksiController extends Controller
{
    // Fungsi untuk menampilkan halaman formulir
    public function showForm()
    {
        return view('form_prediksi');
    }

    // Fungsi untuk memproses data dari formulir dan MEMANGGIL API PYTHON
    public function hitungPrediksi(Request $request)
    {
        // Validasi input dari form
        $validated = $request->validate([
            'nama_surat' => 'required|string',
            'jenis_kelamin' => 'required|string',
            'status_perkawinan' => 'required|string',
            'pekerjaan' => 'required|string',
            'lingkungan' => 'required|string',
            'umur' => 'required|integer',
        ]);

        try {
            // Panggil API Flask di http://127.0.0.1:5000/predict
            // Kita mengirim data sebagai JSON
            $response = Http::post('http://127.0.0.1:5000/predict', $validated);

            if ($response->successful()) {
                // Jika panggilan API berhasil, ambil hasil prediksi dari response JSON
                $hasil = $response->json('prioritas');
                return back()->with('hasil_prediksi', $hasil);
            } else {
                // Jika API mengembalikan status error (misal: error 500)
                return back()->with('error', 'Gagal mendapatkan prediksi dari server ML: ' . $response->body());
            }

        } catch (\Exception $e) {
            // Jika tidak bisa terhubung ke server API Flask sama sekali (misal: server Python belum jalan)
            return back()->with('error', 'Tidak bisa terhubung ke server Machine Learning. Pastikan server API (api.py) sudah berjalan. Pesan: ' . $e->getMessage());
        }
    }
}