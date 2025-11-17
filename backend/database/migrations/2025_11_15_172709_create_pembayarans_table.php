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
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id('id_pembayaran');
            $table->foreignId('id_booking')->constrained('bookings');
            $table->string('metode')->default('qris');
            $table->enum('status_pembayaran', ['pending', 'dibayar', 'gagal'])->default('pending');
            $table->timestamp('tanggal_bayar')->nullable();
            $table->string('id_transaksi')->unique();
            $table->string('link_pembayaran');
            $table->text('qris_data');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};
