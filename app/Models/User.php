<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Role[] $roles
 * @method bool hasRole(string|array $roles)
 */

 /** @mixin \Spatie\Permission\Traits\HasRoles */


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username', 'email', 'password', 'jenis_kelamin'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function kondisiTubuh()
    {
        return $this->hasOne(KondisiTubuh::class);
    }

    public function preferensi()
    {
        return $this->hasOne(Preferensi::class);
    }

    public function histories()
    {
        return $this->hasMany(History::class);
    }

    public function calendarProgress()
    {
        return $this->hasMany(CalendarProgress::class);
    }
}