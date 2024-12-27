<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengusul extends Model
{
    protected $table = 'pengusul';
    protected $fillable = [
        'namaLengkap',
        'username',
        'nik',
        'nomorTelepon',
        'jenisKelamin',        
        'email',
        'password',
    ];

    public function Usulan(){
        return $this->hasMany(Usulan::class, 'id_pengusul');
    }
}
