<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            // Tambah kolom payment_expired_at untuk tracking timeout
            $table->timestamp('payment_expired_at')->nullable()->after('tanggaltransaksi');
            
            // Tambah kolom snap_token untuk Midtrans (jika belum ada)
            if (!Schema::hasColumn('transaksis', 'snap_token')) {
                $table->string('snap_token')->nullable()->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropColumn('payment_expired_at');
            
            // Drop snap_token jika ditambahkan di migration ini
            if (Schema::hasColumn('transaksis', 'snap_token')) {
                $table->dropColumn('snap_token');
            }
        });
    }
};