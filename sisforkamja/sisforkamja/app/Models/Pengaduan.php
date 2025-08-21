<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengaduan extends Model
{
use HasFactory;

// Memperbolehkan semua kolom diisi secara massal untuk kemudahan
protected $guarded = ['id'];

/**
 * Relasi ke model Penduduk (yang membuat laporan).
 */
public function penduduk()
{
    return $this->belongsTo(penduduk::class, 'penduduk_id');
}

/**
 * Relasi ke model User (admin/kepling yang menanggapi).
 */
public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}
}
