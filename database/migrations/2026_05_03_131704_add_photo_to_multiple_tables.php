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
            $table->string('foto', 2048)->nullable()->after('id');
        });

        Schema::table('admins', function (Blueprint $table) {
            $table->string('foto', 2048)->nullable()->after('id'); // gak bisa before, bisanya after doang laravel
        });

        Schema::table('fasilitas', function (Blueprint $table) {
            $table->string('foto', 2048)->nullable()->after('id'); // ganti sesuai kolom terakhirnya
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('foto');
        });

        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn('foto');
        });

        Schema::table('fasilitas', function (Blueprint $table) {
            $table->dropColumn('foto');
        });
    }
};
