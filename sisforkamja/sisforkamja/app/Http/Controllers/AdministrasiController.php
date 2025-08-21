<?php

namespace App\Http\Controllers;

use App\Models\JenisSurat;
use App\Models\PermohonanDokumen;
use App\Models\PermohonanSurat;
use App\Models\User;
use App\Traits\SendsWhatsApp;
use Carbon\Carbon;
use App\Models\penduduk;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AdministrasiController extends Controller
{
    use SendsWhatsApp; 

    public function index()
    {
        return view('pages.administrasi.index');
    }

    public function pengaduan()
    {
        $penduduk = null;
        if (auth()->check() && auth()->user()->nik) {
            $penduduk = Penduduk::where('nik', auth()->user()->nik)->first();
        }
        return view('pages.administrasi.pengaduan', compact('penduduk'));
    }

    public function surat()
    {
        $jenisSurat = JenisSurat::all();
        $penduduk = null;
        if (auth()->check() && auth()->user()->nik) {
            $penduduk = Penduduk::where('nik', auth()->user()->nik)->first();
        }
        return view('pages.administrasi.surat', compact('jenisSurat', 'penduduk'));
    }

    public function store(Request $request)
    {
        try {
            // Validasi dasar yang berlaku untuk semua pengguna
            $request->validate([
                'jenis_surat_id' => 'required|exists:jenis_surat,jenis_surat_id',
                'catatan' => 'nullable|string',
                'file_dokumen_upload.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            ]);

            $penduduk = null;
            $no_telepon_final = null;

            // Logika validasi bercabang berdasarkan status login pengguna
            if (auth()->check()) {
                // --- JIKA PENGGUNA SUDAH LOGIN ---
                $request->validate([
                    'verifikasi_telepon' => ['required', 'digits:4',
                        function ($attribute, $value, $fail) {
                            $user = auth()->user();
                            // Menggunakan relasi untuk mengambil data penduduk
                            $pendudukData = $user->penduduk;
                            
                            if (!$pendudukData || !$pendudukData->no_telepon) {
                                $fail('Data nomor telepon Anda tidak ditemukan di sistem.'); return;
                            }
                            
                            $lastFourDigitsDb = substr($pendudukData->no_telepon, -4);
                            if ($value != $lastFourDigitsDb) {
                                $fail('4 angka terakhir nomor telepon tidak cocok.');
                            }
                        },
                    ],
                ]);
                
                // Ambil data penduduk dari user yang login melalui relasi
                $penduduk = auth()->user()->penduduk;
                $no_telepon_final = $penduduk->no_telepon;

            } else {
                // --- JIKA PENGGUNA BELUM LOGIN (TAMU) ---
                $request->validate([
                    'penduduk_id' => 'required|exists:penduduks,id',
                    'no_telepon' => 'required|string',
                ]);
                $penduduk = Penduduk::find($request->penduduk_id);
                $no_telepon_final = $request->no_telepon;
            }

            // Proses pembuatan permohonan
            $jenisSurat = JenisSurat::find($request->jenis_surat_id);
            $prefix = $jenisSurat->kode_surat;
            $datePart = now()->format('ymd');
            $dailyIndex = PermohonanSurat::whereDate('created_at', today())->count() + 1;
            $indexPart = str_pad($dailyIndex, 2, '0', STR_PAD_LEFT);
            $kodePelacakan = $prefix . '-' . $datePart . $indexPart;

            $permohonan = PermohonanSurat::create([
                'kode_pelacakan' => $kodePelacakan,
                'penduduk_id' => $penduduk->id,
                'jenis_surat_id' => $request->jenis_surat_id,
                'no_telepon' => $no_telepon_final,
                'catatan' => $request->catatan,
                'status' => 'Menunggu',
                'tanggal_permohonan' => Carbon::now(),
                'additional_data' => $request->has('additional_data') ? json_encode($request->additional_data) : null,
            ]);

            // Logika Upload File Dokumen
            if ($request->hasFile('file_dokumen_upload')) {
                foreach ($request->file('file_dokumen_upload') as $file) {
                    $originalName = $file->getClientOriginalName();
                    $filename = time() . '_' . $originalName;
                    $file->storeAs('permohonan_dokumen', $filename, 'public');
                    PermohonanDokumen::create([
                        'permohonan_id' => $permohonan->permohonan_id,
                        'nama_file' => $filename,
                    ]);
                }
            }

            // Blok Integrasi Machine Learning
            try {
                $data_untuk_prediksi = [
                    'nama_surat' => $jenisSurat->nama_surat,
                    'jenis_kelamin' => $penduduk->jenis_kelamin,
                    'status_perkawinan' => $penduduk->status_perkawinan,
                    'pekerjaan' => $penduduk->pekerjaan,
                    'lingkungan' => $penduduk->lingkungan,
                    'umur' => Carbon::parse($penduduk->tanggal_lahir)->age,
                ];
                // Pastikan server API Python Anda berjalan saat testing
                $response = Http::post('http://127.0.0.1:5000/predict', $data_untuk_prediksi);
                if ($response->successful()) {
                    $permohonan->prioritas = $response->json('prioritas');
                    $permohonan->save();
                }
            } catch (\Exception $e) {
                Log::error('Gagal memanggil API prediksi: ' . $e->getMessage());
            }
            
            // Blok Notifikasi WhatsApp dan Admin
            $namaPemohon = $penduduk->nama;
            $pesan =    "[Info Kelurahan Kampung Jawa]\n\n" .
                        "Halo *{$namaPemohon}*,\n\n" .
                        "Permohonan surat Anda telah **berhasil kami terima** dan sedang menunggu untuk diproses oleh admin.\n\n" .
                        "Gunakan kode berikut untuk melacak status permohonan Anda:\n" .
                        "Kode Pelacakan: *{$kodePelacakan}*\n\n" .
                        "Terima kasih.";

            if (!empty($permohonan->no_telepon)) {
                $this->sendWhatsAppNotification($permohonan->no_telepon, $pesan);
            }
            try {
                $admins = User::whereHas('role', function ($query) {
                    $query->where('name', 'Admin'); 
                })->get();
                if ($admins->isNotEmpty()) {
                    \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\NewEntryNotification($permohonan, 'permohonan'));
                }
            } catch (\Exception $e) {
                // Abaikan jika notifikasi ke admin gagal
            }

            return redirect('/administrasi')->with('success', 'Permohonan surat berhasil dikirim! Kode pelacakan telah dikirim ke WhatsApp Anda.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        }
    }

    public function lacak(Request $request)
    {
        $hasil = null;
        $jenisLayanan = null;
        $errorMessage = null;

        if ($request->has('kode') && !empty($request->kode)) {
            $kode = $request->kode;
            
            if (str_starts_with(strtoupper($kode), 'ADU-')) {
                // Ini pengaduan
                $jenisLayanan = 'pengaduan';
                $hasil = \App\Models\Pengaduan::where('kode_pengaduan', $kode)->with('penduduk')->first();
            } else {
                // Ini surat
                $jenisLayanan = 'surat';
                $hasil = PermohonanSurat::where('kode_pelacakan', $kode)->with('penduduk', 'jenisSurat')->first();
            }

            if (!$hasil) {
                $errorMessage = 'Kode Pelacakan tidak ditemukan atau salah. Mohon periksa kembali.';
            }
        }
        return view('pages.administrasi.lacak', compact('hasil', 'jenisLayanan', 'errorMessage'));
    }
}