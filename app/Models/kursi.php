<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class kursi extends Model
{
    protected $fillable = [
        'studio_id',
        'nomorkursi',
        'status',
    ];

    public function studio(){
        return $this->belongsTo(studio::class);
    }

    public function tiket(){
        return $this->belongsTo(tiket::class);
    }
}
