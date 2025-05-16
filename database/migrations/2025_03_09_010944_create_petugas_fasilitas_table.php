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
        Schema::create('petugas_fasilitas', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('name');
            $table->string('no_hp')->unique();
            $table->string('alamat');
            $table->string('email')->nullable()->unique();
            $table->string('password');
            $table->string('foto', 2048)->nullable();
            $table->string('role')->default('petugas_fasilitas');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('petugas_fasilitas');
    }
};
