<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pakan extends Model
{
    protected $table = 'pakan';
    protected $primaryKey = 'id_pakan';
    public $timestamps = false;

    protected $fillable = [
        'nm_pakan',
        'jumlah_stok',
        'tgl_beli'
    ];
}
