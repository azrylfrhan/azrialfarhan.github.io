<?php

namespace App\Http\Controllers;

use App\Models\penduduk;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Exports\PenduduksExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PenduduksImport;

class PendudukController extends Controller
{
    // Method index, create, edit, show, getNamaByNik tidak diubah karena tidak melakukan aksi simpan/ubah/hapus.
    public function index(Request $request)
    {
        $user = auth()->user();
        $queryBuilder = Penduduk::query()->latest();

        // ==========================================================
        // === MULAI LOGIKA PENYARINGAN BERDASARKAN PERAN ===
        // ==========================================================
        // Jika yang login adalah Kepala Lingkungan, saring data berdasarkan lingkungannya
        if ($user->role->name == 'Kepala Lingkungan') {
            $queryBuilder->where('lingkungan', $user->lingkungan);
        }
        // ========================================================

        // Logika pencarian (tidak berubah)
        if ($request->has('search_query') && !empty($request->search_query)) {
            $searchBy = $request->search_by;
            $searchQuery = $request->search_query;
            if ($searchBy == 'nik') {
                $queryBuilder->where('nik', 'like', '%' . $searchQuery . '%');
            } else {
                $queryBuilder->where('nama', 'like', '%' . $searchQuery . '%');
            }
        }

        $penduduks = $queryBuilder->paginate(15)->appends($request->query());
        
        // Sisa kode tidak berubah
        $filterOptions = [
            'lingkungan'        => Penduduk::select('lingkungan')->distinct()->orderBy('lingkungan')->pluck('lingkungan'),
            'jenis_kelamin'     => Penduduk::select('jenis_kelamin')->distinct()->orderBy('jenis_kelamin')->pluck('jenis_kelamin'),
            'agama'             => Penduduk::select('agama')->distinct()->orderBy('agama')->pluck('agama'),
            'status_perkawinan' => Penduduk::select('status_perkawinan')->distinct()->orderBy('status_perkawinan')->pluck('status_perkawinan'),
            'status'            => Penduduk::select('status')->distinct()->orderBy('status')->pluck('status'),
            'pekerjaan'         => Penduduk::select('pekerjaan')->distinct()->orderBy('pekerjaan')->pluck('pekerjaan'),
        ];
        return view('pages.penduduk.index', [
            'penduduks' => $penduduks,
            'filterOptions' => $filterOptions
        ]);
    }

    public function create()
    {
        return view('pages.penduduk.create');
    }

    public function edit($id)
    {
        $penduduks = Penduduk::findOrFail($id);
        return view('pages.penduduk.edit', [
            'penduduks' => $penduduks,
        ]);
    }

    public function show($id)
    {
        return $this->edit($id);
    }
    
    public function getNamaByNik($nik)
    {
        $penduduk = Penduduk::where('nik', $nik)->first();
        if ($penduduk) {
            return response()->json(['nama' => $penduduk->nama, 'id' => $penduduk->id]);
        } else {
            return response()->json(['nama' => null, 'id' => null], 404);
        }
    }

    // --- METHOD-METHOD YANG DIPERBARUI DENGAN TRY-CATCH ---

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'nik' => ['required', 'min:16', 'max:16', 'unique:penduduks,nik'],
                'nama' => ['required', 'max:100'],
                'jenis_kelamin' => ['required', Rule::in(['pria', 'wanita'])],
                'tanggal_lahir' => ['required', 'date'],
                'tempat_lahir' => ['required', 'max:100'],
                'alamat' => ['required', 'max:700'],
                'agama' => ['required', 'max:50'],
                'status_perkawinan' => ['required', Rule::in(['single', 'menikah', 'cerai', 'janda'])],
                'pekerjaan' => ['required', 'max:100'],
                'no_telepon' => ['nullable', 'max:15'],
                'lingkungan' => ['required', Rule::in(['lingkungan1', 'lingkungan2', 'lingkungan3'])],
                'status' => ['required', Rule::in(['aktif', 'pindah', 'meninggal'])],
            ]);

            Penduduk::create($validatedData);
            return redirect('/penduduk')->with('success', 'Data penduduk baru berhasil ditambahkan.');
        } catch (\Exception $e) {
            // Jika validasi atau penyimpanan gagal, kirim pesan error
            return redirect()->back()->with('error', 'Gagal menambahkan data. Pastikan NIK tidak duplikat dan semua data terisi.')->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $penduduk = Penduduk::findOrFail($id);
            $validated = $request->validate([
                'nik' => ['required', 'min:16', 'max:16', Rule::unique('penduduks')->ignore($penduduk->id)],
                'nama' => ['required', 'max:100'],
                'jenis_kelamin' => ['required', Rule::in(['pria', 'wanita'])],
                'tanggal_lahir' => ['required', 'date'],
                'tempat_lahir' => ['required', 'max:100'],
                'alamat' => ['required', 'max:700'],
                'agama' => ['required', 'max:50'],
                'status_perkawinan' => ['required', Rule::in(['single', 'menikah', 'cerai', 'janda'])],
                'pekerjaan' => ['required', 'max:100'],
                'no_telepon' => ['nullable', 'max:15'],
                'lingkungan' => ['required', Rule::in(['lingkungan1', 'lingkungan2', 'lingkungan3'])],
                'status' => ['required', Rule::in(['aktif', 'pindah', 'meninggal'])],
            ]);

            $penduduk->update($validated);
            return redirect('/penduduk')->with('success', 'Data penduduk berhasil diubah.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengubah data penduduk. Silakan coba lagi.')->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $penduduks = Penduduk::findOrFail($id);
            $penduduks->delete();
            return redirect('/penduduk')->with('success', 'Data penduduk berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect('/penduduk')->with('error', 'Gagal menghapus data penduduk. Kemungkinan data ini masih terhubung dengan permohonan surat.');
        }
    }

    public function export(Request $request) 
    {
        try {
            $filterBy = $request->get('filter_by');
            $filterValue = $request->get('filter_value');
            $fileName = 'data_penduduk_' . date('Y-m-d') . '.xlsx';
            return Excel::download(new PenduduksExport($filterBy, $filterValue), $fileName);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal meng-export data. Terjadi kesalahan pada server.');
        }
    }

    public function import(Request $request) 
    {
        try {
            $request->validate(['file_penduduk' => 'required|mimes:xlsx,xls,csv']);
            Excel::import(new PenduduksImport, $request->file('file_penduduk'));
            return redirect('/penduduk')->with('success', 'Data penduduk berhasil diimpor!');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errorMessages = [];
            foreach ($failures as $failure) {
                $errorMessages[] = 'Baris ' . $failure->row() . ': ' . implode(', ', $failure->errors());
            }
            return redirect('/penduduk')->with('error', 'Gagal mengimpor. Kesalahan ditemukan: <br>' . implode('<br>', $errorMessages));
        } catch (\Exception $e) {
            return redirect('/penduduk')->with('error', 'Gagal mengimpor file. Pastikan format kolom di file Excel sudah benar.');
        }
    }

    public function downloadTemplate()
    {
        // Tentukan nama file template
        $namaFile = 'template_penduduk.xlsx';

        // Buat path yang benar dan andal ke file template
        $pathToFile = storage_path('app/public/templates/' . $namaFile);

        // Periksa apakah file benar-benar ada sebelum mencoba mengunduh
        if (!file_exists($pathToFile)) {
            // Jika file tidak ada, kembalikan ke halaman sebelumnya dengan pesan error
            return redirect()->back()->with('error', 'File template tidak ditemukan di server.');
        }

        // Jika file ada, unduh file tersebut
        return response()->download($pathToFile);
    }
}
