<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // tambahkan role
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Accessor untuk nama role
    public function getRoleNameAttribute()
    {
        return match ((int) $this->role) {
            0 => 'owner',
            1 => 'admin',
            2 => 'pegawai gudang',
            3 => 'pembeli',
            4 => 'penitip',
            5 => 'cs',
            6 => 'organisasi',
            default => 'unknown',
        };
    }
}
