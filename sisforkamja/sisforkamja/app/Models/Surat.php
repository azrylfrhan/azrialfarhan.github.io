<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Surat extends Model
{
    use HasFactory;
    protected $table = 'permohonan_surat';
    protected $primaryKey = 'permohonan_surat_id'; // pastikan ini PK di tabel kamu
    protected $fillable = [
        'penduduk_id',
        'jenis_surat_id',
        'tanggal_permohonan',
        'status',
        'file_upload',
    ];

    // Relasi ke jenis surat
    public function jenisSurat()
    {
        return $this->belongsTo(JenisSurat::class, 'jenis_surat_id', 'jenis_surat_id');
    }

    // Relasi ke penduduk
    public function penduduk()
    {
        return $this->belongsTo(Penduduk::class, 'penduduk_id')->withTrashed();
    }
}
