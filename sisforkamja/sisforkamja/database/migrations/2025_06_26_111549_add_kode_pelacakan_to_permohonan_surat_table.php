<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('permohonan_surat', function (Blueprint $table) {
            $table->string('kode_pelacakan')->unique()->nullable()->after('permohonan_id');
        });
    }
    public function down(): void {
        Schema::table('permohonan_surat', function (Blueprint $table) {
            $table->dropColumn('kode_pelacakan');
        });
    }
};