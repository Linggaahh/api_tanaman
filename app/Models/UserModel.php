<?php
// app/Models/UserModel.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
{
    use HasFactory;

    protected $table = 'user';
    protected $primaryKey = 'id_user'; 
    public $timestamps = false;

    protected $fillable = [
        'nama',
        'username',
        'password',
        'gmail',
        'role',
        'profile_picture'
    ];

}
