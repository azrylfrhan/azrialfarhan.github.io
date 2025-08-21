<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\penduduk;

class PermohonanSurat extends Model
{
    use HasFactory;

    // Pengaturan model Anda yang sudah ada
    protected $table = 'permohonan_surat';
    protected $primaryKey = 'permohonan_id';
    protected $fillable = [
        'kode_pelacakan',
        'penduduk_id',
        'jenis_surat_id',
        'no_telepon',
        'catatan',
        'status',
        'tanggal_permohonan',
        'tanggal_selesai',
        'catatan_admin',
        'nomor_surat',
        'additional_data',
    ];

    // Relasi yang sudah ada
    public function penduduk()
    {
        return $this->belongsTo(Penduduk::class, 'penduduk_id', 'id');
    }

    public function jenisSurat()
    {
        return $this->belongsTo(JenisSurat::class, 'jenis_surat_id', 'jenis_surat_id');
    }

    // ===================================================================
    // === PERBAIKAN: Tambahkan method ini untuk mendefinisikan relasi ===
    // === Ini memberitahu Laravel bahwa setiap permohonan memiliki banyak dokumen. ===
    // ===================================================================
    public function dokumen()
    {
        // 'permohonan_id' di tabel anak, 'permohonan_id' di tabel ini (induk)
        return $this->hasMany(PermohonanDokumen::class, 'permohonan_id', 'permohonan_id');
    }
}
