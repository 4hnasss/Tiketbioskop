<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class jadwal extends Model
{

    protected $fillable = ['film_id', 'harga_id', 'studio_id', 'tanggal', 'jamtayang'];

    protected static function booted()
    {
        static::created(function ($jadwal) {
            self::generateSeatsForJadwal($jadwal);
        });
    }

private static function generateSeatsForJadwal($jadwal)
{
    // Hapus kursi lama biar tidak dobel
    \App\Models\Kursi::where('jadwal_id', $jadwal->id)->delete();

    $rows = range('A', 'G'); // 7 baris kursi
    $cols = 12; // 12 kursi per baris

    $seats = [];
    foreach ($rows as $row) {
        for ($i = 1; $i <= $cols; $i++) {
            $seats[] = [
                'jadwal_id' => $jadwal->id,
                'studio_id' => $jadwal->studio_id,
                'nomorkursi' => $row . $i,
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
    }

    \App\Models\Kursi::insert($seats);
}




    public function studio()
    {
        return $this->belongsTo(Studio::class);
    }

    public function transaksi(){
        return $this->belongsTo(transaksi::class);
    }

    public function harga(){
        return $this->belongsTo(harga::class);
    }

    public function film(){
        return $this->belongsTo(film::class);
    }
    
    public function kursis()
    {
        return $this->hasMany(Kursi::class, 'studio_id', 'studio_id'); 
        // Asumsi kursi terkait dengan studio
    }

    public function tiket(){
        return $this->hasMany(tiket::class);
    }
}
