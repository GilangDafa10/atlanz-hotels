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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id('id_booking');
            $table->integer('total_malam');
            $table->integer('total_harga');
            $table->string('status_booking');
            $table->date('tgl_checkin');
            $table->date('tgl_checkout');
            $table->date('tgl_booking');
            $table->foreignId('id_user')->constrained('users');
            $table->foreignId('id_kamar')->constrained('kamars');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
