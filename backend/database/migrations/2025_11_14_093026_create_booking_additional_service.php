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
        Schema::create('booking_additional_service', function (Blueprint $table) {
            $table->foreignId('id_booking')->constrained('bookings');
            $table->foreignId('id_service')->constrained('additional_services');
            $table->integer('harga_saat_booking');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_additional_service');
    }
};
