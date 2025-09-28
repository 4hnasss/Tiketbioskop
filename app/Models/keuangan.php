<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class keuangan extends Model
{
    protected $fillable = [
        'transaksi_id',
        'totalpemesanan',
        'tanggal',
    ];

    public function transaksi(){
        return $this->hasMany(transaksi::class);
    }
}
