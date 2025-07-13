<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Pembeli extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    public $timestamps = false;
    protected $table = 'pembeli';
    protected $primaryKey = 'id_pembeli';
    protected $fillable = [
        'id_pembeli',
        'username',
        'email',
        'password',
        'foto',
        'poin_pembeli',
        'fcm_token',
    ];
    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected $casts = [
        'id_pembeli' => 'string', 
    ];

    public function alamat()
    {
        return $this->hasMany(Alamat::class, 'id_pembeli', 'id_pembeli');
    }

    public function carts()
    {
        return $this->hasMany(Cart::class, 'id_pembeli');
    }
}
