<?php

namespace App\Exports;

use App\Models\penduduk;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\Exportable;

// 1. Import class yang diperlukan untuk solusi baru
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;

// 2. Implementasikan WithCustomValueBinder dan extends StringValueBinder
// Ini adalah perubahan kunci yang akan "memaksa" semua sel menjadi teks.
class PenduduksExport extends StringValueBinder implements FromQuery, WithHeadings, ShouldAutoSize, WithCustomValueBinder
{
    use Exportable;

    protected $filter_by;
    protected $filter_value;

    public function __construct(?string $filter_by = null, ?string $filter_value = null)
    {
        $this->filter_by = $filter_by;
        $this->filter_value = $filter_value;
    }

    public function query()
    {
        $query = Penduduk::query();

        if ($this->filter_by && $this->filter_value && $this->filter_by !== 'all') {
            $query->where($this->filter_by, $this->filter_value);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'ID', 'NIK', 'Nama Lengkap', 'Jenis Kelamin', 'Tanggal Lahir', 'Tempat Lahir',
            'Alamat', 'Agama', 'Status Perkawinan', 'Pekerjaan', 'No Telepon',
            'Status Penduduk', 'Lingkungan', 'Dibuat Pada', 'Diperbarui Pada',
        ];
    }

    // CATATAN: Method columnFormats() tidak diperlukan lagi dengan solusi ini,
    // karena StringValueBinder sudah menangani semuanya secara global.
}
