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
        Schema::create('kursis', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('jadwal_id')->constrained()->onDelete('cascade');
            $table->foreignId('studio_id')->constrained()->onDelete('cascade');
            $table->string('nomorkursi');
            $table->enum('status', ['tersedia', 'tidaktersedia', 'dipesan', 'terjual'])->default('tersedia');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kursis');
    }
};
