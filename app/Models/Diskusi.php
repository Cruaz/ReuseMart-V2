<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diskusi extends Model
{
    use HasFactory;
    protected $table = 'diskusi';
    protected $primaryKey = 'id_diskusi';
    public $timestamps = false;
    protected $fillable = [
        'id_diskusi',
        'id_pembeli',
        'id_barang',
        'id_pegawai',
        'pertanyaan_diskusi',
        'jawaban_diskusi',
    ];

    protected $casts = [
        'id_image' => 'string', 
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }

    public function pembeli()
    {
        return $this->belongsTo(Pembeli::class, 'id_pembeli');
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai');
    }
}