<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QLearningState extends Model
{
    use HasFactory;

    protected $fillable = [
        'usia', 'jenis_kelamin', 'kategori_bmi',
        'kondisi_kesehatan', 'tingkat_kebugaran',
        'jenis_olahraga_favorit', 'tujuan_workout',
        'durasi_latihan', 'kelengkapan_alat',
        'mood', 'workout_id', 'q_value'
    ];

    public function workout()
    {
        return $this->belongsTo(Workout::class);
    }
}