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
        Schema::create('pemasukans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('checkout_id')->constrained()->onDelete('cascade');
            $table->foreignId('fasilitas_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->date('tanggal_bayar');
            $table->bigInteger('jumlah_bayar');
            $table->enum('metode_pembayaran', ['transfer', 'qris', 'lainnya'])->default('qris');
            $table->string('bukti_bayar')->nullable();
            $table->enum('status', ['lunas', 'pending', 'ditolak'])->default('lunas');
            $table->text('keterangan')->nullable();
            $table->foreignId('admin_id')->nullable()->constrained('admins')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemasukans');
    }
};
