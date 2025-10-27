<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Jadwal;
use Carbon\Carbon;

class HapusJadwalLewat extends Command
{
    // Signature command yang dipanggil di terminal
    protected $signature = 'jadwal:hapus-lewat';
    protected $description = 'Hapus semua jadwal yang sudah lewat dari hari ini';

    public function handle()
    {
        $now = Carbon::now();

    $deleted = Jadwal::where(function($q) use ($now) {
        $q->where('tanggal', '<', $now->toDateString())
        ->orWhere(function($q2) use ($now) {
            $q2->where('tanggal', $now->toDateString())
                ->where('jamtayang', '<', $now->format('H:i:s'));
        });
    })->delete();

    $this->info("Berhasil menghapus $deleted jadwal yang sudah lewat.");
    }
}
