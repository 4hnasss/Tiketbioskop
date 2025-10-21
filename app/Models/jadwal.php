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

        Kursi::insert($seats);
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

    public function kursi(){
        return $this->hasMany(kursi::class);
    }

    public function tiket(){
        return $this->hasMany(tiket::class);
    }
}
