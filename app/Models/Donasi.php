<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donasi extends Model
{
    use HasFactory;

    protected $table = 'donasi';
    protected $primaryKey = 'id_barang';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id_barang',
        'id_request',
        'tanggal_donasi',
        'nama_penerima',
        'status_donasi',
    ];

    protected $casts = [
        'tanggal_donasi' => 'date',
    ];

    public function getTanggalDonasiAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('Y-m-d');
    }

    public function request()
    {
        return $this->belongsTo(RequestDonasi::class, 'id_request');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id_barang');
    }
}
