<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->string('snap_token')->nullable()->after('totalharga');
            // jika mau ubah nama kolom metode_bayar -> metode_pembayaran
            if (Schema::hasColumn('transaksis', 'metode_bayar')) {
                $table->renameColumn('metode_bayar', 'metode_pembayaran');
            } else {
                $table->string('metode_pembayaran')->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropColumn(['snap_token', 'metode_pembayaran']);
            // jika ingin restore rename, tidak ditangani otomatis
        });
    }
};
