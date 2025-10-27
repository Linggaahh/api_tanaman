<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supply extends Model
{
    protected $table = 'supply';
    protected $primaryKey = 'id_supply';
    public $timestamps = false;

    protected $fillable = [
        'id_panen',
        'pengiriman',
        'tgl_kirim',
        'jumlah_kirim',
        'jenis_produk'
    ];

    public function panen()
    {
        return $this->belongsTo(Panen::class, 'id_panen', 'id_panen');
    }
}
