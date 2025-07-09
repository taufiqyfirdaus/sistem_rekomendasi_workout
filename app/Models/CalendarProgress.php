<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarProgress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'tanggal', 'is_done'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}