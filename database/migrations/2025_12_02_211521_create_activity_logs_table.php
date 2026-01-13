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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            // Siapa yang melakukan? (Bisa Admin atau User)
            $table->foreignId('admin_id')->nullable()->constrained('admins')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            // Apa yang dilakukan?
            $table->string('action'); // Contoh: 'CREATE_FASILITAS', 'CANCEL_BOOKING', 'VALIDASI_PEMBAYARAN'
            $table->text('description')->nullable(); // Detail: 'Menambahkan lapangan Futsal B'
            // Terhadap objek apa? (Polymorphic sederhana)
            $table->string('subject_type')->nullable();
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
