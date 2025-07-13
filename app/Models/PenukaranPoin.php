<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenukaranPoin extends Model
{
    use HasFactory;

    protected $table = 'penukaranpoin';
    protected $primaryKey = 'id_penukaran';

    protected $fillable = [
        'id_pembeli',
        'id_merchandise',
        'tanggal_penukaran',
        'jumlah_poin_terpakai',
        'tanggal_pengambilan',
    ];

    protected $dates = [
        'tanggal_penukaran',
        'tanggal_pengambilan',
        'created_at',
        'updated_at'
    ];

    /**
     * Get the pembeli that owns the PenukaranPoin
     */
    public function pembeli()
    {
        return $this->belongsTo(Pembeli::class, 'id_pembeli');
    }

    /**
     * Get the merchandise that owns the PenukaranPoin
     */
    public function merchandise()
    {
        return $this->belongsTo(Merchandise::class, 'id_merchandise');
    }
}