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
            $table->unsignedBigInteger('id_booking');
            $table->unsignedBigInteger('id_service');

            $table->foreign('id_booking')
                ->references('id_booking')->on('bookings')
                ->onDelete('cascade');

            $table->foreign('id_service')
                ->references('id_service')->on('additional_services')
                ->onDelete('cascade');
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
