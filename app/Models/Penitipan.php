<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penitipan extends Model
{
    use HasFactory;

    protected $table = 'penitipan';
    protected $primaryKey = 'id_penitipan';
    public $timestamps = false;

    protected $fillable = [
        'id_penitipan',
        'id_penitip',
        'id_pegawai',
        'peg_id_pegawai',
        'tanggal_penitipan',
        'masa_penitipan',
        'batas_pengambilan',
        'status_perpanjangan',
        'tanggal_konfirmasi_pengambilan',
    ];

    // Relasi ke Penitip (asumsinya ada model Penitip)
    public function penitip()
    {
        return $this->belongsTo(Penitip::class, 'id_penitip', 'id_penitip');
    }

    // Relasi ke Pegawai utama
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai');
    }

    // Relasi ke Pegawai tambahan (peg_id_pegawai)
    public function hunter()
    {
        return $this->belongsTo(Pegawai::class, 'peg_id_pegawai');
    }

    public function penitipanBarang()
    {
        return $this->hasMany(PenitipanBarang::class, 'id_penitipan', 'id_penitipan');
    }

    public function barang()
    {
        return $this->hasManyThrough(
            Barang::class,
            PenitipanBarang::class,
            'id_penitipan', // Foreign key on penitipanbarang table
            'id_barang',    // Foreign key on barang table
            'id_penitipan', // Local key on penitipan table
            'id_barang'     // Local key on penitipanbarang table
        );
    }

}
