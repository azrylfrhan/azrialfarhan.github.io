<?php

namespace App\Http\Controllers;

use App\Models\JenisSurat;
use App\Models\PermohonanSurat;
use App\Traits\SendsWhatsApp; // 1. Import Trait "Mesin Notifikasi"
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use PhpOffice\PhpWord\TemplateProcessor;

class SuratController extends Controller
{
    use SendsWhatsApp; // 2. Gunakan Trait di dalam class

    /**
     * Menampilkan daftar permohonan surat dengan pagination.
     */
    public function index(Request $request)
    {
        // Mulai query builder, jangan langsung get()/paginate()
        $query = PermohonanSurat::with('penduduk', 'jenisSurat')->latest();

        // Cek apakah ada input pencarian
        if ($request->has('query') && !empty($request->query('query'))) {
            $searchBy = $request->query('search_by');
            $searchQuery = $request->query('query');

            switch ($searchBy) {
                case 'kode_pelacakan':
                    $query->where('kode_pelacakan', 'like', '%' . $searchQuery . '%');
                    break;

                case 'nama':
                    // Mencari di tabel relasi 'penduduk'
                    $query->whereHas('penduduk', function ($q) use ($searchQuery) {
                        $q->where('nama', 'like', '%' . $searchQuery . '%');
                    });
                    break;
                
                case 'tanggal':
                    // Mencari berdasarkan tanggal permohonan
                    $query->whereDate('tanggal_permohonan', $searchQuery);
                    break;
            }
        }

        // Ambil data setelah difilter, lalu lakukan pagination
        // appends() akan memastikan filter tetap aktif saat berpindah halaman pagination
        $data = $query->paginate(10)->appends($request->all());

        return view('pages.mohonsurat.index', compact('data'));
    }

    public function arsip()
    {
        // Ambil semua permohonan yang statusnya sudah Selesai
        // Urutkan dari yang paling baru
        $dataArsip = PermohonanSurat::with('penduduk', 'jenisSurat')
            ->where('status', 'Selesai')
            ->latest('tanggal_selesai')
            ->paginate(10); // Kita gunakan pagination juga di sini

        return view('pages.arsipsurat.index', compact('dataArsip'));
    }

    /**
     * Menampilkan halaman manajemen Jenis Surat.
     */
    public function jenis_surat()
    {
        $jenis_surat = JenisSurat::all();
        return view('pages.jenis_surat.index', compact('jenis_surat'));
    }

    /**
     * Menyimpan Jenis Surat baru.
     */
    public function store(Request $request)
{
    try {
        // Validasi data dasar DAN input tambahan
        $request->validate([
            'nama_surat' => 'required|string|max:255|unique:jenis_surat,nama_surat',
            'kode_surat' => 'required|string|max:50|unique:jenis_surat,kode_surat',
            'template_surat' => 'nullable|mimes:doc,docx|max:2048',
            'persyaratan' => 'nullable|string',
            // Validasi baru untuk form builder
            'custom_fields' => 'nullable|array',
            'custom_fields.*.label' => 'required_with:custom_fields|string',
            'custom_fields.*.type' => 'required_with:custom_fields|string|in:text,textarea,date,number',
            'custom_fields.*.placeholder' => 'nullable|string',
        ]);

        // Memproses data dari form builder menjadi format JSON
        $customFieldsData = [];
        if ($request->has('custom_fields')) {
            foreach ($request->custom_fields as $field) {
                if (!empty($field['label'])) {
                    $customFieldsData[] = [
                        'name' => \Illuminate\Support\Str::snake(strtolower($field['label'])), 
                        'label' => $field['label'],
                        'type' => $field['type'],
                        'placeholder' => $field['placeholder'] ?? '',
                    ];
                }
            }
        }

        $path = null;
        if ($request->hasFile('template_surat')) {
            $path = $request->file('template_surat')->store('templates', 'public');
        }

        // Menyimpan data ke database, termasuk custom_fields
        JenisSurat::create([
            'nama_surat' => $request->nama_surat,
            'kode_surat' => $request->kode_surat,
            'template_surat' => $path ? basename($path) : null,
            'persyaratan' => $request->persyaratan,
            'custom_fields' => count($customFieldsData) > 0 ? json_encode($customFieldsData) : null,
        ]);

        return redirect('/jenis-surat')->with('success', 'Jenis Surat berhasil ditambahkan!');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Gagal menambahkan Jenis Surat. Pastikan data unik dan sesuai format.')->withInput();
    }
}


    public function update(Request $request, $jenis_surat_id)
    {
        try {
            $jenis_surat = \App\Models\JenisSurat::findOrFail($jenis_surat_id);
            
            // 1. Validasi untuk data dasar DAN input tambahan
            $request->validate([
                'nama_surat' => ['required', 'string', 'max:255', \Illuminate\Validation\Rule::unique('jenis_surat')->ignore($jenis_surat->jenis_surat_id, 'jenis_surat_id')],
                'kode_surat' => ['required', 'string', 'max:50', \Illuminate\Validation\Rule::unique('jenis_surat')->ignore($jenis_surat->jenis_surat_id, 'jenis_surat_id')],
                'template_surat' => 'nullable|mimes:doc,docx|max:2048',
                'persyaratan' => 'nullable|string',
                // Validasi baru untuk form builder
                'custom_fields' => 'nullable|array',
                'custom_fields.*.label' => 'required_with:custom_fields|string',
                'custom_fields.*.type' => 'required_with:custom_fields|string|in:text,textarea,date,number',
                'custom_fields.*.placeholder' => 'nullable|string',
            ]);

            // 2. Memproses data dari form builder menjadi format JSON yang benar
            $customFieldsData = [];
            if ($request->has('custom_fields')) {
                foreach ($request->custom_fields as $field) {
                    // Hanya proses jika label diisi untuk menghindari baris kosong
                    if (!empty($field['label'])) {
                        $customFieldsData[] = [
                            // Membuat 'name' teknis dari 'label' (Contoh: "Nama Usaha" menjadi "nama_usaha")
                            'name' => \Illuminate\Support\Str::snake(strtolower($field['label'])), 
                            'label' => $field['label'],
                            'type' => $field['type'],
                            'placeholder' => $field['placeholder'] ?? '',
                        ];
                    }
                }
            }

            // 3. Menyimpan data dasar Anda (tidak ada perubahan di sini)
            $jenis_surat->nama_surat = $request->nama_surat;
            $jenis_surat->kode_surat = $request->kode_surat;
            $jenis_surat->persyaratan = $request->persyaratan;
            
            // 4. Menyimpan data input tambahan yang sudah diproses
            $jenis_surat->custom_fields = count($customFieldsData) > 0 ? json_encode($customFieldsData) : null;

            // Logika upload file Anda (tidak ada perubahan di sini)
            if ($request->hasFile('template_surat')) {
                if ($jenis_surat->template_surat) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete('templates/' . $jenis_surat->template_surat);
                }
                $path = $request->file('template_surat')->store('templates', 'public');
                $jenis_surat->template_surat = basename($path);
            }

            // Simpan semua perubahan ke database
            $jenis_surat->save();
            
            return redirect('/jenis-surat')->with('success', 'Jenis Surat berhasil diupdate!');

        } catch (\Exception $e) {
            // Menangkap error jika ada
            return redirect()->back()->with('error', 'Gagal mengupdate Jenis Surat. Silakan coba lagi. Error: ' . $e->getMessage());
        }
    }

    public function destroy($jenis_surat_id)
    {
        try {
            $jenis_surat = JenisSurat::findOrFail($jenis_surat_id);
            if ($jenis_surat->template_surat) {
                Storage::disk('public')->delete('templates/' . $jenis_surat->template_surat);
            }
            $jenis_surat->delete();
            return redirect('/jenis-surat')->with('success', 'Jenis Surat berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect('/jenis-surat')->with('error', 'Gagal menghapus. Kemungkinan jenis surat ini masih digunakan pada data permohonan.');
        }
    }



    public function editModal(Request $request, $id)
    {
        // Membungkus seluruh logika di dalam blok try-catch
        try {
            // 1. Validasi input (kode Anda sudah benar)
            $request->validate([
                'status' => 'required|in:Menunggu,Diproses,Selesai,Ditolak',
                'catatan_admin' => 'required_if:status,Ditolak|nullable|string|max:500',
            ]);

            // 2. Cari dan update permohonan (kode Anda sudah benar)
            $permohonan = \App\Models\PermohonanSurat::with('penduduk', 'jenisSurat')->findOrFail($id);
            $permohonan->status = $request->status;
            $permohonan->catatan_admin = $request->catatan_admin;

            if ($request->status === 'Selesai' && !$permohonan->tanggal_selesai) {
                $permohonan->tanggal_selesai = now();
            }

            $permohonan->save();

            // ===================================================================
            // === BAGIAN YANG DIKEMBALIKAN (LOGIKA NOTIFIKASI WHATSAPP ANDA) ===
            // ===================================================================
            $nomorTujuan = $permohonan->no_telepon;
            $namaPemohon = $permohonan->penduduk->nama;
            $jenisSurat = $permohonan->jenisSurat->nama_surat;
            $kodePelacakan = $permohonan->kode_pelacakan;
            $pesan = '';
            
            $header = "[Info Kelurahan Kampung Jawa]\n\n";
            $footer = "\n\nTerima kasih,\nPemerintah Kelurahan Kampung Jawa";

            if ($request->status === 'Diproses') {
                $pesan = $header . "Halo *{$namaPemohon}*,\n\n" .
                "Permohonan surat Anda dengan kode *{$kodePelacakan}* telah kami terima dan saat ini sedang dalam proses.\n\n" .
                "Kami akan memberitahu Anda kembali jika sudah selesai." . $footer;
            } elseif ($request->status === 'Selesai') {
                $pesan = $header . "Halo *{$namaPemohon}*,\n\n" .
                "Kabar baik! Surat Anda dengan kode *{$kodePelacakan}* telah **SELESAI** diproses dan sudah dapat diambil di kantor kelurahan pada jam kerja.\n\n" .
                "**Penting:** Mohon untuk membawa KTP asli sebagai syarat pengambilan." . $footer;
            } elseif ($request->status === 'Ditolak') {
                $alasan = $request->catatan_admin ?: 'Tidak ada alasan spesifik.';
                $pesan = $header . "Halo *{$namaPemohon}*,\n\n" .
                "Dengan hormat, kami memberitahukan bahwa permohonan surat Anda dengan kode *{$kodePelacakan}* **DITOLAK** dengan alasan: *{$alasan}*\n\n" .
                "Silakan perbaiki permohonan Anda atau hubungi kantor kelurahan untuk informasi lebih lanjut." . $footer;
            }

            // Kirim notifikasi jika ada pesan yang harus dikirim
            if (!empty($pesan) && !empty($nomorTujuan)) {
                $this->sendWhatsAppNotification($nomorTujuan, $pesan);
            }
            // ===================================================================
            // === AKHIR DARI BAGIAN YANG DIKEMBALIKAN ===
            // ===================================================================

            // Jika semua berhasil, kirim pesan 'success'
            return redirect()->back()->with('success', 'Status permohonan berhasil diubah dan notifikasi WhatsApp telah dikirim!');

        } catch (\Exception $e) {
            // Jika terjadi error, kirim pesan 'error'
            return redirect()->back()->with('error', 'Gagal mengubah status permohonan. Silakan coba lagi.');
        }
    }





    public function generateSurat($id)
    {
        try {
            $permohonan = PermohonanSurat::with('penduduk', 'jenisSurat')->findOrFail($id);

            if (empty($permohonan->jenisSurat->template_surat)) {
                return redirect()->back()->with('error', 'Template surat untuk jenis ini tidak tersedia.');
            }

            $templatePath = storage_path('app/public/templates/' . $permohonan->jenisSurat->template_surat);
            
            if (!file_exists($templatePath)) {
                return redirect()->back()->with('error', 'File template tidak ditemukan di server.');
            }

            $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);

            // --- LOGIKA BARU PEMBUATAN NOMOR SURAT ---
            if (empty($permohonan->nomor_surat)) {
                $bulanRomawi = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
                $bulanIni = now()->month;
                $tahunIni = now()->year;
                $kodeSurat = $permohonan->jenisSurat->kode_surat;

                // 1. Hitung surat sejenis yang sudah ada di tahun ini
                $jumlahSuratSebelumnya = PermohonanSurat::where('jenis_surat_id', $permohonan->jenis_surat_id)
                                                    ->whereYear('tanggal_permohonan', $tahunIni)
                                                    ->whereNotNull('nomor_surat') // Hanya hitung yang sudah punya nomor
                                                    ->count();
                
                // 2. Tentukan nomor urut berikutnya
                $nomorUrut = $jumlahSuratSebelumnya + 1;

                // 3. Format nomor urut dengan 3 digit (misalnya, 001)
                $nomorUrutFormatted = str_pad($nomorUrut, 3, '0', STR_PAD_LEFT);

                // 4. Gabungkan semua bagian menjadi format final
                $nomorSuratFinal = $nomorUrutFormatted . '/' . $kodeSurat . '/' . $bulanRomawi[$bulanIni - 1] . '/' . $tahunIni;

                // 5. Simpan nomor surat baru ke database
                $permohonan->nomor_surat = $nomorSuratFinal;
                // Update tanggal selesai saat surat digenerate jika belum ada
                if (empty($permohonan->tanggal_selesai)) {
                    $permohonan->tanggal_selesai = now();
                }
                $permohonan->save();
            }
            // --- AKHIR LOGIKA BARU ---

            // --- Mengganti Placeholder Standar ---
            $penduduk = $permohonan->penduduk;
            $templateProcessor->setValue('nomor_surat', $permohonan->nomor_surat);
            $templateProcessor->setValue('tanggal_surat', \Carbon\Carbon::now()->translatedFormat('d F Y'));
            $templateProcessor->setValue('nama_pemohon', $penduduk->nama);
            $templateProcessor->setValue('nik', $penduduk->nik);
            $templateProcessor->setValue('ttl', $penduduk->tempat_lahir . ', ' . \Carbon\Carbon::parse($penduduk->tanggal_lahir)->translatedFormat('d F Y'));
            $templateProcessor->setValue('jenis_kelamin', $penduduk->jenis_kelamin);
            $templateProcessor->setValue('agama', $penduduk->agama);
            $templateProcessor->setValue('pekerjaan', $penduduk->pekerjaan);
            $templateProcessor->setValue('alamat', $penduduk->alamat);
            
            // --- Mengganti Placeholder dari Input Tambahan ---
            $additionalData = json_decode($permohonan->additional_data, true);
            if (is_array($additionalData)) {
                foreach ($additionalData as $key => $value) {
                    $templateProcessor->setValue($key, htmlspecialchars($value ?? ''));
                }
            }

            // --- Logika Penyimpanan dan Download File ---
            $namaFileArsip = 'Surat ' . $permohonan->jenisSurat->nama_surat . ' - ' . $penduduk->nama . ' (' . $permohonan->kode_pelacakan . ').docx';
            $folderArsip = storage_path('app/public/arsip_surat/');

            if (!file_exists($folderArsip)) {
                mkdir($folderArsip, 0755, true);
            }
            
            $pathToFileArsip = $folderArsip . $namaFileArsip;
            $templateProcessor->saveAs($pathToFileArsip);

            return response()->download($pathToFileArsip);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membuat surat. Pesan Error: ' . $e->getMessage());
        }
    }


    public function getPersyaratan($id)
    {
        // Cari jenis surat berdasarkan ID
        $jenisSurat = JenisSurat::find($id);

        // Jika tidak ditemukan, kirim error 404
        if (!$jenisSurat) {
            return response()->json(['error' => 'Jenis surat tidak ditemukan'], 404);
        }

        // Ubah string persyaratan menjadi array
        $persyaratanArray = $jenisSurat->persyaratan ? explode("\n", $jenisSurat->persyaratan) : [];

        // ===================================================================
        // === BAGIAN YANG DIPERBAIKI ===
        // Sekarang kita juga mengirimkan 'custom_fields'
        // ===================================================================
        return response()->json([
            'nama_surat'    => $jenisSurat->nama_surat,
            'persyaratan'   => $persyaratanArray,
            'custom_fields' => json_decode($jenisSurat->custom_fields) ?? [] // Kirim custom_fields
        ]);
    }

}