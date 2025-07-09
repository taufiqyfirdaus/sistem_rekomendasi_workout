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
        Schema::create('kondisi_tubuh', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('tanggal_lahir');
            $table->float('berat');
            $table->float('tinggi');
            $table->enum('kondisi_kesehatan', ['Normal', 'Cedera', 'Hipertensi', 'Hipotensi', 'Diabetes', 'Obesitas', 'Penyakit Jantung', 'Asma']);
            $table->enum('tingkat_kebugaran', ['Rendah', 'Sedang', 'Tinggi']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kondisi_tubuh');
    }
};