<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestDonasi extends Model
{
    use HasFactory;

    protected $table = 'requestdonasi';
    protected $primaryKey = 'id_request';
    public $timestamps = false;

    protected $fillable = [
        'id_request',
        'id_organisasi',
        'tanggal_request',
        'status_request',
        'deskripsi_request',
    ];

    protected $casts = [
        'tanggal_request' => 'date',
    ];

    public function getTanggalRequestAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('Y-m-d');
    }

    public function organisasi()
    {
        return $this->belongsTo(Organisasi::class, 'id_organisasi');
    }
    
}
