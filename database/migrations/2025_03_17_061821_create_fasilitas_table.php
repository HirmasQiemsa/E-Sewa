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
        Schema::create('fasilitas', function (Blueprint $table) {
            $table->id();
            $table->string('foto', 2048)->nullable();
            $table->string('nama_fasilitas');
            $table->text('deskripsi')->nullable();
            $table->string('tipe')->nullable();
            $table->string('lokasi');
            $table->integer('harga_sewa')->default(value: 0);
            $table->enum('ketersediaan', ['aktif', 'nonaktif','maintanace'])->default('aktif');
            $table->foreignId('petugas_fasilitas_id')->nullable()->constrained('petugas_fasilitas')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fasilitas');
    }
};
