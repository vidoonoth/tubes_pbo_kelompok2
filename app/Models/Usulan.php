<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usulan extends Model
{
    protected $table = 'usulan';
    protected $fillable = [
        'judulBuku',
        'isbn',
        'kategori',
        'penulis',
        'penerbit',        
        'tahunTerbit',
        'tanggalPengusulan',
        'halaman',
        'sinopsis'
    ];

    public function pengusul(){
        return $this->belongsTo(Pengusul::class, 'id_pengusul');
    }
}
