<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Komisi extends Model
{
    use HasFactory;

    protected $table = 'komisi';

    public $incrementing = false; 

    public $timestamps = false;

    protected $fillable = [
        'id_transaksi',
        'id_penitip',
        'id_pegawai',
        'komisi_hunter',
        'komisi_reusemart',
        'bonus_penitip',
    ];
    

    // Relasi ke model Penitip
    public function penitip()
    {
        return $this->belongsTo(Penitip::class, 'id_penitip');
    }

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'id_transaksi');
    }

    // Relasi ke model Pegawai
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai');
    }
}
