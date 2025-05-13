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
        Schema::create('pemasukan_sewas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('checkout_id')->constrained()->onDelete('cascade');
            $table->foreignId('fasilitas_id')->constrained()->onDelete('cascade');
            $table->date('tanggal_bayar');
            $table->bigInteger('jumlah_bayar');
            $table->enum('metode_pembayaran', ['cash', 'transfer', 'qris', 'lainnya'])->default('cash');
            $table->string('bukti_pembayaran', 2048)->nullable();
            $table->enum('status', ['fee', 'lunas'])->default('fee');
            $table->foreignId('petugas_pembayaran_id')->nullable()->constrained('petugas_pembayarans')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemasukan_sewas');
    }
};
