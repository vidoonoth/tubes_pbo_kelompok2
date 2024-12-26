<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengusul extends Model
{
    protected $table = 'pengusuls';
    protected $fillable = [
        'namaLengkap',
        'username',
        'nik',
        'nomorTelepon',
        'jenisKelamin',        
        'email',
        'password',
    ];
}
