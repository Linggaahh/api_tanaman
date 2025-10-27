<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kandang extends Model
{
    protected $table = 'kandang';
    protected $primaryKey = 'id_kandang';
    public $timestamps = false;

    protected $fillable = [
        'nm_kandang',
        'kapasitas',
        'jumlah_hewan',
        'jenis_hewan',
        'keterangan'
    ];

}
