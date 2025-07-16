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
        Schema::table('preferensi', function (Blueprint $table) {
            $table->dropColumn('durasi');
        });

        Schema::table('preferensi', function (Blueprint $table) {
            $table->enum('durasi', ['<=30 menit', '<=60 menit', '<=120 menit'])->after('tujuan_workout');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('preferensi', function (Blueprint $table) {
            $table->dropColumn('durasi');
        });

        Schema::table('preferensi', function (Blueprint $table) {
            $table->integer('durasi')->after('tujuan_workout');
        });
    }
};