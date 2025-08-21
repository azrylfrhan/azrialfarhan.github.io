<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('dokumen_permohonan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('permohonan_id');
            $table->string('nama_file');
            $table->timestamps();

            // Foreign key ke permohonan_surat
            $table->foreign('permohonan_id')
                ->references('permohonan_id')
                ->on('permohonan_surat')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('dokumen_permohonan');
    }
};
