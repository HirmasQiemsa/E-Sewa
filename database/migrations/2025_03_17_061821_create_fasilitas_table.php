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
            $table->string('foto')->nullable();
            $table->string('nama_fasilitas');
            $table->text('deskripsi')->nullable();
            $table->string('tipe')->nullable();
            $table->string('kategori')->nullable();
            $table->string('lokasi');
            $table->integer('harga_sewa')->default(0);
            $table->enum('ketersediaan', ['aktif', 'nonaktif', 'maintenance'])->default('nonaktif');
            // $table->enum('status_approval', ['draft', 'pending', 'approved', 'rejected'])->default('draft');
            $table->foreignId('admin_fasilitas_id')->nullable()->constrained('admins')->nullOnDelete();
            $table->foreignId('admin_pembayaran_id')->nullable()->constrained('admins')->nullOnDelete();
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
