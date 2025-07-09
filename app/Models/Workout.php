<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workout extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_workout', 'tingkat_kesulitan', 'kategori',
        'durasi', 'alat', 'ilustrasi', 'deskripsi', 'instruksi'
    ];

    public function histories()
    {
        return $this->hasMany(History::class);
    }

    public function qLearningStates()
    {
        return $this->hasMany(QLearningState::class);
    }
}