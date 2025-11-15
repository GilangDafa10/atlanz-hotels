<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fasilitas_jenis_kamar', function (Blueprint $table) {
            $table->id('id_fasilitas_jenis_kamar');
            $table->unsignedBigInteger('id_fasilitas');
            $table->unsignedBigInteger('id_jenis_kamar');
            $table->timestamps();

            // foreign keys (sesuaikan jika nama PK berbeda)
            $table->foreign('id_fasilitas')->references('id_fasilitas')->on('fasilitas')->onDelete('cascade');
            $table->foreign('id_jenis_kamar')->references('id_jenis_kamar')->on('jenis_kamar')->onDelete('cascade');

            // optional: unik kombinasi fasilitas + jenis_kamar agar tidak duplikat
            $table->unique(['id_fasilitas', 'id_jenis_kamar']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fasilitas_jenis_kamar');
    }
};
