<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('permohonan_surat', function (Blueprint $table) {
            $table->string('nomor_surat')->unique()->nullable()->after('kode_pelacakan');
        });
    }
    public function down(): void {
        Schema::table('permohonan_surat', function (Blueprint $table) {
            $table->dropColumn('nomor_surat');
        });
    }
};