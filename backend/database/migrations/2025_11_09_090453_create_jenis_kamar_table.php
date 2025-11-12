<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jenis_kamar', function (Blueprint $table) {
            $table->id('id_jenis_kamar');
            $table->string('jenis_kasur');
            $table->integer('harga_permalam');
            $table->text('deskripsi')->nullable();
            $table->string('url_gambar')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jenis_kamar');
    }
};
