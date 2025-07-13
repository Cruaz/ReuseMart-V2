<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';
    protected $primaryKey = 'id_barang';
    public $timestamps = false;
    protected $fillable = [
        'id_transaksi',
        'nama_barang',
        'harga_barang',
        'kategori_barang',
        'status_garansi_barang',
        'tanggal_habis_garansi',
        'deskripsi_barang',
        'review_barang',
        'berat_barang',
        'status_barang',
        'gambar_barang',
    ];

    protected $casts = [
        'harga_barang' => 'float',
        'berat_barang' => 'float',
    ];

    public function getStatusGaransiAttribute()
    {
        if (!$this->tanggal_habis_garansi) {
            return 'Tidak Bergaransi';
        }

        return Carbon::parse($this->tanggal_habis_garansi)->isFuture()
            ? 'Masih Bergaransi'
            : 'Garansi Habis';
    }

    public function diskusi()
    {
        return $this->hasMany(Diskusi::class, 'id_barang');
    }

    public function carts()
    {
        return $this->hasMany(Cart::class, 'id_barang');
    }

   public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'id_transaksi');
    }

    public function donasi()
    {
        return $this->hasOne(\App\Models\Donasi::class, 'id_barang', 'id_barang');
    }

    public function penitipanBarang()
    {
        return $this->hasMany(PenitipanBarang::class, 'id_barang', 'id_barang');
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai', 'id_pegawai');
    }
}
