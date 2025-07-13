<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alamat extends Model
{
    use HasFactory;
    protected $table = 'alamat';
    protected $primaryKey = 'id_alamat';
    public $timestamps = false;
    protected $fillable = [
        'id_alamat',
        'id_pembeli',
        'label_alamat',
        'deskripsi_alamat',
        'is_default',
    ];

    protected $casts = [
        'id_image' => 'string', 
    ];

    public function Pembeli()
    {
        return $this->belongsTo(Pembeli::class, 'id_pembeli');
    }
}
