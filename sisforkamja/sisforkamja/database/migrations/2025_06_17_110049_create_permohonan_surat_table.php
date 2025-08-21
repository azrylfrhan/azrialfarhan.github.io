<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Karena nama tabel Anda bentuk tunggal, kita gunakan 'permohonan_surat'
        Schema::create('permohonan_surat', function (Blueprint $table) {
            // Kolom Primary Key
            $table->id('permohonan_id');

            // Foreign Keys (Menghubungkan ke tabel lain)
            $table->foreignId('penduduk_id')->constrained('penduduks');
            $table->foreignId('jenis_surat_id')->constrained('jenis_surat', 'jenis_surat_id');
            $table->foreignId('user_id')->nullable()->constrained('users'); // Admin yang memproses

            // Kolom untuk Status dan Detail Permohonan
            $table->enum('status', ['Menunggu', 'Diproses', 'Selesai', 'Ditolak'])->default('Menunggu');
            $table->string('no_telepon', 20);

            // Kolom Catatan (Saya asumsikan 'catatan' dari CSV adalah 'catatan_pemohon')
            $table->text('catatan')->nullable(); // Ini adalah catatan dari pemohon
            $table->text('catatan_admin')->nullable(); // Ini adalah catatan dari admin

            // Kolom Tanggal
            $table->timestamp('tanggal_permohonan')->nullable();
            $table->timestamp('tanggal_selesai')->nullable();
            $table->timestamps(); // Ini akan membuat created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permohonan_surat');
    }
};