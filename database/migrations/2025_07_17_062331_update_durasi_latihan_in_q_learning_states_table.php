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
        Schema::table('q_learning_states', function (Blueprint $table) {
            $table->dropColumn('durasi_latihan');
        });

        Schema::table('q_learning_states', function (Blueprint $table) {
            $table->enum('durasi_latihan', ['<=30 menit', '<=60 menit', '<=120 menit'])->after('tujuan_workout');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('q_learning_states', function (Blueprint $table) {
            $table->dropColumn('durasi_latihan');
        });

        Schema::table('prefeq_learning_states', function (Blueprint $table) {
            $table->integer('durasi_latihan')->after('tujuan_workout');
        });
    }
};