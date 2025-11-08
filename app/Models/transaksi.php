<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'jadwal_id',
        'kursi',
        'tanggaltransaksi',
        'totalharga',
        'status',
        'metode_bayar', // Sesuai dengan kolom di database Anda
        'payment_expired_at', // ✅ KOLOM BARU untuk timeout
        'snap_token', // ✅ Untuk menyimpan Midtrans token
    ];

    protected $casts = [
        'kursi' => 'array', // Auto convert JSON ke array
        'tanggaltransaksi' => 'datetime',
        'payment_expired_at' => 'datetime', // ✅ CAST ke datetime
        'totalharga' => 'decimal:2',
    ];

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Jadwal
     */
    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }

    /**
     * Relasi ke Tiket (one to many)
     */
    public function tikets()
    {
        return $this->hasMany(Tiket::class);
    }

    /**
     * ✅ Helper: Check apakah pembayaran sudah expired
     */
    public function isExpired()
    {
        if (!$this->payment_expired_at) {
            return false;
        }

        return now()->greaterThan($this->payment_expired_at) && $this->status === 'pending';
    }

    /**
     * ✅ Helper: Get sisa waktu dalam detik
     */
    public function getRemainingSeconds()
    {
        if (!$this->payment_expired_at || $this->status !== 'pending') {
            return 0;
        }

        $remaining = now()->diffInSeconds($this->payment_expired_at, false);
        return max(0, $remaining);
    }

    /**
     * ✅ Accessor: Format kursi untuk display
     */
    public function getKursiDisplayAttribute()
    {
        if (is_array($this->kursi)) {
            return implode(', ', $this->kursi);
        }
        
        if (is_string($this->kursi)) {
            $kursiArray = json_decode($this->kursi, true);
            if (is_array($kursiArray)) {
                return implode(', ', $kursiArray);
            }
        }
        
        return $this->kursi ?? '-';
    }

    /**
     * ✅ Scope: Ambil transaksi yang expired
     */
    public function scopeExpired($query)
    {
        return $query->where('status', 'pending')
            ->whereNotNull('payment_expired_at')
            ->where('payment_expired_at', '<', now());
    }

    /**
     * ✅ Scope: Ambil transaksi pending yang belum expired
     */
    public function scopePendingNotExpired($query)
    {
        return $query->where('status', 'pending')
            ->where(function($q) {
                $q->whereNull('payment_expired_at')
                  ->orWhere('payment_expired_at', '>=', now());
            });
    }
}