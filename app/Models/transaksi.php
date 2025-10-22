<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class transaksi extends Model
{
    protected $fillable = [
        'user_id',
        'jadwal_id',
        'tanggaltransaksi',
        'order_id',
        'totalharga',
        'status',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function tiket(){
        return $this->belongsTo(tiket::class);
    }

    public function keuangan(){
        return $this->belongsTo(keuangan::class);
    }

    public function jadwal(){
        return $this->belongsTo(jadwal::class);
    }
}
