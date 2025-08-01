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
        Schema::table('pembeli', function (Blueprint $table) {
            $table->rememberToken()->nullable();
        });
        Schema::table('organisasi', function (Blueprint $table) {
            $table->rememberToken()->nullable();
        });
        Schema::table('penitip', function (Blueprint $table) {
            $table->rememberToken()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembeli', function (Blueprint $table) {
            $table->dropColumn('remember_token');
        });
        Schema::table('organisasi', function (Blueprint $table) {
            $table->dropColumn('remember_token');
        });
        Schema::table('penitip', function (Blueprint $table) {
            $table->dropColumn('remember_token');
        });
    }
};
