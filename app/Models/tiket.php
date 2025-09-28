<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class tiket extends Model
{
    protected $fillable = [
        'transaksi_id',
        'kursi_id',
        'jadwal_id',
        'kodetiket',
    ];

    public function transaksi(){
        return 
        $this->belongsTo(transaksi::class);
    }

    public function kursi(){
        return $this->hasMany(kursi::class);
    }

}
