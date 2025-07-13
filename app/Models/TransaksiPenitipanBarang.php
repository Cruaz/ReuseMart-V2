<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiPenitipanBarang extends Model
{
    protected $table = 'penitipanbarang';
    protected $primaryKey = 'id_penitipan_barang';
    
    public function penitipan()
    {
        return $this->belongsTo(Penitipan::class, 'id_penitipan');
    }
    
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }
}