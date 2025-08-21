    <?php
    
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        public function up(): void
        {
            Schema::table('users', function (Blueprint $table) {
                // Kolom ini akan diisi jika rolenya adalah Kepala Lingkungan
                $table->string('lingkungan')->nullable()->after('role_id');
            });
        }

        public function down(): void
        {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('lingkungan');
            });
        }
    };
    