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
        // 1. Laporan Booking
        Schema::create('laporan_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('checkout_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['fee', 'lunas', 'batal']);
            $table->date('tanggal_booking');
            $table->timestamps();
        });

        // 2. Laporan Pemasukan Sewa
        Schema::create('laporan_pemasukan_sewas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pemasukan_sewa_id')->constrained('pemasukan_sewas')->onDelete('cascade');
            $table->unsignedTinyInteger('bulan');
            $table->unsignedSmallInteger('tahun');
            $table->bigInteger('total_pemasukan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_pemasukan_sewas');
        Schema::dropIfExists('laporan_bookings');
    }
};
