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
        // // 1. Laporan Rekap Penggunaan
        // Schema::create('laporan_rekap_penggunaans', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('checkout_id')->constrained()->onDelete('cascade');
        //     $table->integer('jumlah_checkout')->default(0);
        //     $table->unsignedTinyInteger('bulan');
        //     $table->unsignedSmallInteger('tahun');
        //     $table->timestamps();
        // });

        // // 2. Laporan Fasilitas
        // Schema::create('laporan_fasilitas', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('checkout_id')->constrained()->onDelete('cascade');
        //     $table->foreignId('fasilitas_id')->constrained()->onDelete('cascade');
        //     $table->foreignId('user_id')->constrained()->onDelete('cascade');
        //     $table->decimal('jumlah_jam', 5, 2);
        //     $table->timestamps();
        // });

        // // 3. Laporan Jadwal
        // Schema::create('laporan_jadwals', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('jadwal_id')->constrained('jadwals')->onDelete('cascade');
        //     $table->enum('status', ['tersedia', 'terbooking', 'selesai', 'batal']);
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_jadwals');
        Schema::dropIfExists('laporan_fasilitas');
        Schema::dropIfExists('laporan_rekap_penggunaans');
    }
};
