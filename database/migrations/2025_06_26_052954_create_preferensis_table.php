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
        Schema::create('preferensi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('jenis_olahraga_favorit', ['Kardio', 'Bodyweight Training', 'Fleksibilitas', 'Dance Fitness', 'HIIT', 'Kekuatan']);
            $table->enum('tujuan_workout', [
                'Menurunkan Berat Badan',
                'Meningkatkan Massa Otot & Kekuatan',
                'Meningkatkan Kebugaran Kardiovaskular',
                'Meningkatkan Fleksibilitas',
                'Relaksasi'
            ]);
            $table->integer('durasi');
            $table->enum('alat', ['Tidak ada', 'Dasar', 'Lengkap']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preferensi');
    }
};