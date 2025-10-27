<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Panen extends Model
{
    protected $table = 'panen';
    protected $primaryKey = 'id_panen';
    public $timestamps = false;

    protected $fillable = [
        'tgl_panen',
        'jenis_panen',
        'jumlah',
        'kualitas',
        'id_tumbuhan',
        'id_ternak'
    ];

}
