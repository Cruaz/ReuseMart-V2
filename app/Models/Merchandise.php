<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Merchandise extends Model
{
    use HasFactory;

    protected $table = 'merchandise';
    protected $primaryKey = 'id_merchandise';
    public $timestamps = false;

    protected $fillable = [
        'id_merchandise',
        'nama_merchandise',
        'poin_redeem',
        'stok_merchandise',
    ];

    protected $casts = [
        'poin_redeem' => 'integer',
        'stok_merchandise' => 'integer',
    ];
}
