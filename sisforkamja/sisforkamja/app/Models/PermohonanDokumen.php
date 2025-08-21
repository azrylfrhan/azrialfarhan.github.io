<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermohonanDokumen extends Model
{
    protected $table = 'dokumen_permohonan';
    protected $fillable = ['permohonan_id', 'nama_file'];

    public function permohonan()
    {
        return $this->belongsTo(PermohonanSurat::class, 'permohonan_id');
    }
}
