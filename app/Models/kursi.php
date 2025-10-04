<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class kursi extends Model
{
    protected $fillable = [
        'jadwal_id',
        'nomorkursi',
        'status',
    ];


    public function tiket(){
        return $this->belongsTo(tiket::class);
    }

    public function jadwal(){
        return $this->belongsTo(jadwal::class);
    }
}
