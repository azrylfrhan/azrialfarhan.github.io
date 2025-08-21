<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisSurat extends Model
{
    protected $table = 'jenis_surat';

    protected $fillable = [
        'nama_surat',
        'kode_surat',
        'template_surat',
        'persyaratan',
        'custom_fields',
    ];
    // kasih tahu primary key nya bukan id, misalnya:
    protected $primaryKey = 'jenis_surat_id';

    // kalau primary key bukan auto-increment integer:
    public $incrementing = false; // kalau string
    protected $keyType = 'string'; // kalau string, atau 'int' kalau integer
}
