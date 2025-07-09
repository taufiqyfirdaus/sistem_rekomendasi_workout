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
        Schema::create('q_learning_states', function (Blueprint $table) {
            $table->id();

            // Kondisi Tubuh
            $table->string('usia');
            $table->enum('jenis_kelamin', ['Pria', 'Wanita']);
            $table->string('kategori_bmi');
            $table->enum('kondisi_kesehatan', ['Normal', 'Cedera', 'Hipertensi', 'Hipotensi', 'Diabetes', 'Obesitas', 'Penyakit Jantung', 'Asma']);
            $table->enum('tingkat_kebugaran', ['Rendah', 'Sedang', 'Tinggi']);

            // Preferensi
            $table->enum('jenis_olahraga_favorit', ['Kardio', 'Bodyweight Training', 'Fleksibilitas', 'Dance Fitness', 'HIIT', 'Kekuatan']);
            $table->enum('tujuan_workout', [
                'Menurunkan Berat Badan',
                'Meningkatkan Massa Otot & Kekuatan',
                'Meningkatkan Kebugaran Kardiovaskular',
                'Meningkatkan Fleksibilitas',
                'Relaksasi'
            ]);
            $table->integer('durasi_latihan');
            $table->enum('kelengkapan_alat', ['Tidak ada', 'Dasar', 'Lengkap']);

            // Mood dan Aksi
            $table->enum('mood', ['Bagus', 'Buruk']);
            $table->foreignId('workout_id')->constrained()->onDelete('cascade');
            $table->float('q_value')->default(0);

            $table->timestamps();

            // INDEX untuk mempercepat pencarian berdasarkan kombinasi state + mood
            $table->index([
                'usia',
                'jenis_kelamin',
                'kategori_bmi',
                'kondisi_kesehatan',
                'tingkat_kebugaran',
                'jenis_olahraga_favorit',
                'tujuan_workout',
                'durasi_latihan',
                'kelengkapan_alat',
                'mood',
            ], 'q_learning_state_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('q_learning_states');
    }
};