<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi';
    protected $primaryKey = 'id_transaksi';
    protected $fillable = [
        'id_temp_transaksi',
        'id_pembeli',
        'id_alamat',
        'id_pegawai',
        'tanggal_transaksi',
        'harga_total_barang',
        'status_transaksi',
        'tanggal_pengembalian',
        'tanggal_lunas',
        'opsi_pengiriman',
        'jadwal_pengiriman',
        'potongan_harga',
        'harga_ongkir',
        'poin_pembeli',
        'poin_spent',
        'bukti_pembayaran',
        'nomor_transaksi',
        'create_at',
    ];

    protected $casts = [
        'harga_total_barang' => 'float',
        'potongan_harga' => 'float',
        'harga_ongkir' => 'float',
    ];

    public function pembeli()
    {
        return $this->belongsTo(Pembeli::class,'id_pembeli', 'id_pembeli');
    }

   

    public function alamat()
    {
        return $this->belongsTo(Alamat::class, 'id_alamat', 'id_alamat');
    }


    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class,'id_pegawai');
    }

    
    public function barang()
    {
        return $this->hasMany(Barang::class, 'id_transaksi', 'id_transaksi');
    }

    public function komisi()
    {
        return $this->hasOne(Komisi::class, 'id_transaksi', 'id_transaksi');
    }

}
