<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KondisiTubuh extends Model
{
    use HasFactory;

    protected $table = 'kondisi_tubuh';

    protected $fillable = [
        'user_id', 'tanggal_lahir', 'berat', 'tinggi',
        'kondisi_kesehatan', 'tingkat_kebugaran'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}