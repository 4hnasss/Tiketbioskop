<?php

namespace App\Console;

use App\Models\jadwal;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */

    protected function jadwal(jadwal $jadwal)
    {
        // Jalankan setiap 5 menit
        $jadwal->command('transaksi:cancel-expired')->everyFiveMinutes();
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
