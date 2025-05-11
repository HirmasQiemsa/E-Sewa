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
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('user'); // Default role = user
        });
        Schema::table('admins', function (Blueprint $table) {
            $table->string('role')->default('admin'); // Default role = admin
        });
        Schema::table('petugas_pembayarans', function (Blueprint $table) {
            $table->string('role')->default('petugas_pembayaran'); // Default role = petugas_pembayaran
        });
        Schema::table('petugas_fasilitas', function (Blueprint $table) {
            $table->string('role')->default('petugas_fasilitas'); // Default role = petugas_fasilitas
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
