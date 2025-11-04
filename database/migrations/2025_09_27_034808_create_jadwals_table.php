<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('film_id')->constrained()->onDelete('cascade');
            $table->foreignId('studio_id')->constrained()->onDelete('cascade');
            $table->foreignId('harga_id')->constrained()->onDelete('cascade');
            $table->date('tanggal');
            $table->time('jamtayang');
            $table->timestamps();
            
            // Tambahkan unique constraint
            $table->unique(['studio_id', 'tanggal', 'jamtayang'], 'unique_jadwal');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwals');
    }
};