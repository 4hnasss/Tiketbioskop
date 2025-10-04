<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class jadwal extends Model
{
    protected $fillable = [
        'film_id',
        'harga_id',
        'tanggal',
        'jamtayang',
        'studio'
    ];

    public function transaksi(){
        return $this->belongsTo(transaksi::class);
    }

    public function harga(){
        return $this->belongsTo(harga::class);
    }

    public function film(){
        return $this->belongsTo(film::class);
    }

    public function kursi(){
        return $this->hasMany(kursi::class);
    }

    public function tiket(){
        return $this->hasMany(tiket::class);
    }
}
