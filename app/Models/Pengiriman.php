<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengiriman extends Model
{

    protected $table = 'pengiriman';

    protected $primaryKey = 'id_pengiriman';

    public $timestamps = false;


    protected $fillable = [
        'id_supply',
        'id_panen',
        'tgl_pengiriman',
        'tujuan',
        'jumlah_dikirim',
        'status_pengiriman', 
        'id_kurir',
        'keterangan'
    ];


    const STATUS_PENDING = 'pending';
    const STATUS_SELESAI = 'selesai';


    public function getStatusPengirimanLabelAttribute()
    {
        return ucfirst($this->status_pengiriman);
    }


    public function supply()
    {
        return $this->belongsTo(Supply::class, 'id_supply');
    }

    public function kurir()
    {
        return $this->belongsTo(UserModel::class, 'id_kurir');
    }
}
