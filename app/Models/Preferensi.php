<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Preferensi extends Model
{
    use HasFactory;

    protected $table = 'preferensi';

    protected $fillable = [
        'user_id', 'jenis_olahraga_favorit',
        'tujuan_workout', 'durasi', 'alat'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}