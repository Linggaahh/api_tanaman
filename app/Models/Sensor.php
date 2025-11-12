<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sensor extends Model
{
    protected $table = 'sensor';
    protected $primaryKey = 'id_sensor';
    public $timestamps = false;

    protected $fillable = [
        'id_tanaman',
        'id_kandang',
        'lokasi',
        'populasi',
        'suhu',
        'kelembapan',
        'produktivitas',
        'status_kesehatan',
        'waktu'
    ];

    public function tanaman()
    {
        return $this->belongsTo(Tanaman::class, 'id_tanaman', 'id_tanaman');
    }

    public function kandang()
    {
        return $this->belongsTo(Kandang::class, 'id_kandang', 'id_kandang');
    }
}
