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
            Schema::table('pengaduans', function (Blueprint $table) {
                // Menambahkan kolom untuk menyimpan nomor telepon khusus untuk pengaduan ini
                $table->string('no_telepon', 20)->nullable()->after('penduduk_id');
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::table('pengaduans', function (Blueprint $table) {
                $table->dropColumn('no_telepon');
            });
        }
    };
    