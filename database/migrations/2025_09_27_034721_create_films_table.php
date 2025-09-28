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
        Schema::create('films', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('studio_id')->constrained()->onDelete('cascade');
            $table->string('judul')->nullable();
            $table->string('genre')->nullable();
            $table->integer('durasi')->nullable();
            $table->text('deskripsi')->nullable();
            $table->enum('status', ['upcomming', 'playnow'])->default('upcomming')->nullable();
            $table->string('poster')->nullable();
            $table->date('tanggalmulai')->nullable();
            $table->date('tanggalselesai')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('films');
    }
};
