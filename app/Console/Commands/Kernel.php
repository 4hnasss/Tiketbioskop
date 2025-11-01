<?php

namespace App\Console;

use App\Models\jadwal;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function Jadwal(jadwal $jadwal): void
    {
        // Jalankan command hapus jadwal setiap hari pukul 00:01
        $jadwal->command('jadwal:hapus-lewat')->dailyAt('00:01');
    }

    protected $commands = [
        \App\Console\Commands\HapusJadwalLewat::class,
    ];


    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
