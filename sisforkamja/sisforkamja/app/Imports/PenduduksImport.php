<?php

namespace App\Imports;

use App\Models\penduduk;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Carbon\Carbon;

class PenduduksImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Logika Anda untuk menyimpan atau memperbarui data tetap sama
        return Penduduk::updateOrCreate(
            ['nik' => $row['nik']], // Kunci untuk mencari duplikasi
            [
                'nama'              => $row['nama_lengkap'],
                'jenis_kelamin'     => strtolower($row['jenis_kelamin']),
                'tanggal_lahir'     => Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['tanggal_lahir'])),
                'tempat_lahir'      => $row['tempat_lahir'],
                'alamat'            => $row['alamat'],
                'agama'             => $row['agama'],
                'status_perkawinan' => strtolower($row['status_perkawinan']),
                'pekerjaan'         => $row['pekerjaan'],
                'no_telepon'        => $row['no_telepon'],
                'status'            => strtolower($row['status_penduduk']),
                'lingkungan'        => strtolower($row['lingkungan']),
            ]
        );
    }

    /**
     * Aturan validasi Anda tetap sama.
     */
    public function rules(): array
    {
        return [
            'nik' => 'required|digits:16',
            'nama_lengkap' => 'required|string',
            // ... (aturan validasi Anda yang lain)
        ];
    }

    // ===================================================================
    // === PERBAIKAN 3: Tambahkan method ini ===
    // Ini memberitahu sistem untuk memproses file dalam potongan 100 baris.
    // ===================================================================
    public function chunkSize(): int
    {
        return 100;
    }
}
