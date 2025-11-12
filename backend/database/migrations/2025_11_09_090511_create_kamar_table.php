<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kamar', function (Blueprint $table) {
            $table->id('id_kamar');
            $table->string('nomor_kamar');
            $table->unsignedBigInteger('id_jenis_kamar');
            $table->timestamps();

            $table->foreign('id_jenis_kamar')
                  ->references('id_jenis_kamar')
                  ->on('jenis_kamar')
                  ->cascadeOnDelete()
                  ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kamar');
    }
};
