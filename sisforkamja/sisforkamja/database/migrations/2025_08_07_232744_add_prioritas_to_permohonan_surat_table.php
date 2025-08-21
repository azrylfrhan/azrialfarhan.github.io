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
        Schema::table('permohonan_surat', function (Blueprint $table) {
            // Menambahkan kolom 'prioritas' setelah kolom 'status'
            // Nullable berarti kolom ini boleh kosong
            $table->string('prioritas')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permohonan_surat', function (Blueprint $table) {
            //
        });
    }
};
