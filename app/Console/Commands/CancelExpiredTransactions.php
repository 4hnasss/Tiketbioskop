<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaksi;
use App\Models\Kursi;
use Carbon\Carbon;

class CancelExpiredTransactions extends Command
{
    protected $signature = 'transaksi:cancel-expired';
    protected $description = 'Cancel expired pending transactions and release seats';

    public function handle()
    {
        // Transaksi pending lebih dari 30 menit dianggap expired
        $expiredTransactions = Transaksi::where('status', 'pending')
            ->where('created_at', '<', Carbon::now()->subMinutes(30))
            ->get();

        foreach ($expiredTransactions as $transaksi) {
            // Update status transaksi
            $transaksi->status = 'batal';
            $transaksi->save();

            // Kembalikan status kursi jadi tersedia
            $kursiList = is_array($transaksi->kursi) 
                ? $transaksi->kursi 
                : json_decode($transaksi->kursi, true);

            if (is_array($kursiList)) {
                foreach ($kursiList as $nomorKursi) {
                    Kursi::where('jadwal_id', $transaksi->jadwal_id)
                        ->where('nomorkursi', $nomorKursi)
                        ->update(['status' => 'tersedia']);
                }
            }

            $this->info("Transaksi #{$transaksi->id} dibatalkan dan kursi dikembalikan");
        }

        $this->info("Total {$expiredTransactions->count()} transaksi expired dibatalkan");
        return 0;
    }
}