<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class studio extends Model
{
    protected $fillable = [
        'nama',
    ];

    public function jadwal(){
        return $this->hasMany(jadwal::class);
    }

    public function film(){
        return $this->belongsTo(film::class);
    }

    public function kursi(){
        return $this->hasMany(kursi::class);
    }
}
