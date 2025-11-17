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
        Schema::create('booking_kamar', function (Blueprint $table) {
            $table->unsignedBigInteger('id_booking');
            $table->unsignedBigInteger('id_kamar');
            $table->integer('harga_saat_booking');
            $table->primary(['id_booking', 'id_kamar']);
            $table->foreign('id_booking')
                ->references('id_booking')
                ->on('bookings')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreign('id_kamar')
                ->references('id_kamar')
                ->on('kamar')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_kamar');
    }
};
