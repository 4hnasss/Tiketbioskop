<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class film extends Model
{
    protected $fillable =[
        'judul',
        'genre',
        'durasi',
        'deskripsi',
        'status',
        'poster',
        'tanggalmulai',
        'tanggalselesai',
    ];

    public function jadwal(){
        return $this->hasMany(jadwal::class);
    }

}
