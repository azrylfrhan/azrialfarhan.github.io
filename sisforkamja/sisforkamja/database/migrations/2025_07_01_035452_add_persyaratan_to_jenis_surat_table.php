    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        public function up(): void
        {
            Schema::table('jenis_surat', function (Blueprint $table) {
                // Kolom untuk menyimpan daftar persyaratan, dipisahkan oleh baris baru
                $table->text('persyaratan')->nullable()->after('template_surat');
            });
        }

        public function down(): void
        {
            Schema::table('jenis_surat', function (Blueprint $table) {
                $table->dropColumn('persyaratan');
            });
        }
    };
    