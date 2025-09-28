<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class harga extends Model
{
    protected $fillable = [
        'jenis',
        'harga',
    ];

    public function jadwal(){
        return $this->hasMany(jadwal::class);
    }
}
