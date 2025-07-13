<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Organisasi extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    public $timestamps = false;
    protected $table = 'organisasi';
    protected $primaryKey = 'id_organisasi';
    protected $fillable = [
        'id_organisasi',
        'email',
        'username',
        'password',
        'foto',
        'alamat_organisasi'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];
    
    protected $casts = [
        'id_organisasi' => 'string',
    ];
}
