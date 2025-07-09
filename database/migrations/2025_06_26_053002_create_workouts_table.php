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
        Schema::create('workouts', function (Blueprint $table) {
            $table->id();
            $table->string('nama_workout');
            $table->enum('tingkat_kesulitan', ['Pemula', 'Menengah', 'Ahli']);
            $table->enum('kategori', [
                'Kardio',
                'Bodyweight Training',
                'Fleksibilitas',
                'Dance Fitness',
                'HIIT',
                'Kekuatan'
            ]);
            $table->integer('durasi');
            $table->string('alat');
            $table->text('ilustrasi')->nullable();
            $table->text('deskripsi')->nullable();
            $table->text('instruksi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workouts');
    }
};