    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        public function up(): void
        {
            Schema::create('pengaduans', function (Blueprint $table) {
                $table->id();
                $table->string('kode_pengaduan')->unique();
                $table->foreignId('penduduk_id')->constrained('penduduks')->onDelete('cascade');
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null'); // Admin/Kepling yg menanggapi
                $table->enum('kategori', ['Infrastruktur', 'Kebersihan', 'Keamanan', 'Layanan Publik', 'Lainnya']);
                $table->string('judul');
                $table->text('isi_laporan');
                $table->string('foto_bukti')->nullable();
                $table->enum('status', ['Baru', 'Dalam Peninjauan', 'Ditindaklanjuti', 'Selesai', 'Ditolak'])->default('Baru');
                $table->text('tanggapan_admin')->nullable();
                $table->timestamp('tanggal_tanggapan')->nullable();
                $table->timestamps();
            });
        }

        public function down(): void
        {
            Schema::dropIfExists('pengaduans');
        }
    };
    