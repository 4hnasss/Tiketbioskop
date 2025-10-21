<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class studio extends Model
{
    protected $fillable = ['nama_studio'];

    public function kursi()
    {
        return $this->hasMany(Kursi::class);
    }

    public function jadwals()
    {
        return $this->hasMany(Jadwal::class);
    }

    public function harga()
    {
        return $this->belongsTo(Harga::class, 'harga_id');
    }
}
