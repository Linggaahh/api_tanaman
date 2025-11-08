<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tanaman extends Model
{
    protected $table = 'tanaman';
    protected $primaryKey = 'id_tanaman';
    public $timestamps = false;
    protected $fillable = [
        'nm_tanaman',
        'varietas',
        'jumlah',
        'tgl_tanam',
        'lama_panen',
        'lokasi',
        'status'
    ];
}
