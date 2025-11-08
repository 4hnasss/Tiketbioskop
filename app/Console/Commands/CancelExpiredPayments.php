<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaksi;
use App\Models\Kursi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CancelExpiredPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment:cancel-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancel expired payment transactions and release seats';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for expired payments...');
        
        Log::info('=== Payment Cleanup Started ===');

        // Cari transaksi pending yang sudah expired
        $expiredTransactions = Transaksi::where('status', 'pending')
            ->whereNotNull('payment_expired_at')
            ->where('payment_expired_at', '<', now())
            ->get();

        if ($expiredTransactions->isEmpty()) {
            $this->info('No expired transactions found.');
            Log::info('No expired transactions found.');
            return 0;
        }

        $count = 0;

        foreach ($expiredTransactions as $transaksi) {
            try {
                DB::beginTransaction();

                $oldStatus = $transaksi->status;

                // Update status transaksi
                $transaksi->status = 'expired';
                $transaksi->save();

                // Kembalikan kursi jadi tersedia
                $this->releaseSeats($transaksi);

                DB::commit();

                $count++;
                $this->info("✅ Cancelled transaction ID: {$transaksi->id}");

                Log::info('Payment expired and cancelled', [
                    'transaksi_id' => $transaksi->id,
                    'old_status' => $oldStatus,
                    'new_status' => 'expired',
                    'expired_at' => $transaksi->payment_expired_at,
                    'kursi' => $transaksi->kursi
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                $this->error("❌ Error cancelling transaction ID {$transaksi->id}: {$e->getMessage()}");
                Log::error('Error cancelling expired transaction', [
                    'transaksi_id' => $transaksi->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }

        $this->info("Successfully cancelled {$count} expired transaction(s).");
        Log::info('=== Payment Cleanup Completed ===', ['total_cancelled' => $count]);
        
        return 0;
    }

    /**
     * Release seats back to available status
     */
    private function releaseSeats($transaksi)
    {
        $kursiList = is_array($transaksi->kursi) 
            ? $transaksi->kursi 
            : json_decode($transaksi->kursi, true);

        if (!is_array($kursiList)) {
            Log::warning('Invalid kursi data', [
                'transaksi_id' => $transaksi->id,
                'kursi' => $transaksi->kursi
            ]);
            $this->warn("  ⚠️ Invalid kursi data for transaction {$transaksi->id}");
            return;
        }

        $releasedCount = 0;

        foreach ($kursiList as $nomorKursi) {
            $updated = Kursi::where('jadwal_id', $transaksi->jadwal_id)
                ->where('nomorkursi', $nomorKursi)
                ->where('status', '!=', 'terjual') // Jangan ubah kursi yang sudah terjual
                ->update(['status' => 'tersedia']);

            if ($updated) {
                $releasedCount++;
                $this->info("  - Released seat: {$nomorKursi}");
                
                Log::info('Seat released', [
                    'transaksi_id' => $transaksi->id,
                    'jadwal_id' => $transaksi->jadwal_id,
                    'nomor_kursi' => $nomorKursi,
                    'new_status' => 'tersedia'
                ]);
            } else {
                $this->warn("  ⚠️ Could not release seat: {$nomorKursi} (might be already sold)");
            }
        }

        $this->info("  Total seats released: {$releasedCount}/{" . count($kursiList) . "}");
    }
}