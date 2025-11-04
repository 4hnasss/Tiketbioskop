<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class jadwal extends Model
{
    protected $fillable = ['film_id', 'harga_id', 'studio_id', 'tanggal', 'jamtayang'];

    protected static function booted()
    {
        // Validasi sebelum create
        static::creating(function ($jadwal) {
            self::validateJadwalBentrok($jadwal);
        });

        // Validasi sebelum update
        static::updating(function ($jadwal) {
            self::validateJadwalBentrok($jadwal);
        });

        static::created(function ($jadwal) {
            self::generateSeatsForJadwal($jadwal);
        });
    }

    private static function validateJadwalBentrok($jadwal)
    {
        $exists = self::where('studio_id', $jadwal->studio_id)
            ->where('tanggal', $jadwal->tanggal)
            ->where('jamtayang', $jadwal->jamtayang)
            ->when($jadwal->exists, function ($query) use ($jadwal) {
                // Exclude jadwal yang sedang di-update
                return $query->where('id', '!=', $jadwal->id);
            })
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'studio_id' => 'Studio ini sudah memiliki jadwal pada tanggal dan jam yang sama.'
            ]);
        }
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

    public function transaksi()
    {
        return $this->belongsTo(transaksi::class);
    }

    public function harga()
    {
        return $this->belongsTo(Harga::class, 'harga_id');
    }

    public function film()
    {
        return $this->belongsTo(film::class);
    }
    
    public function kursis()
    {
        return $this->hasMany(Kursi::class, 'studio_id', 'studio_id'); 
    }

    public function tiket()
    {
        return $this->hasMany(tiket::class);
    }
}