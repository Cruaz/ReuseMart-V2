<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenitipanBarang extends Model
{
    public $timestamps = false;
    public $incrementing = false; // karena pakai composite key
    protected $table = 'penitipanbarang';

    protected $primaryKey = ['id_penitipan', 'id_barang'];

    protected $fillable = [
        'id_penitipan',
        'id_barang',
    ];

    public function getKeyName()
    {
        return $this->primaryKey;
    }

    public function penitipan()
    {
        return $this->belongsTo(Penitipan::class, 'id_penitipan', 'id_penitipan');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id_barang');
    }
}
