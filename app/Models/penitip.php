<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Penitip extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'penitip';
    protected $primaryKey = 'id_penitip';
    public $timestamps = false;

    protected $fillable = [
        'id_penitip',
        'username',
        'email',
        'password',
        'nik',
        'foto',
        'saldo',
        'poin_performa',
        'status_badge',
        'fcm_token',
    ];

    protected $casts = [
        'poin_performa' => 'integer',
        'id_penitip' => 'string',
    ];

    public function penitipan()
    {
        return $this->hasMany(Penitipan::class, 'id_penitip', 'id_penitip');
    }
 
    
}
