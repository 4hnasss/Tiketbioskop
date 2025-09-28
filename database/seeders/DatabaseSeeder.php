<?php

namespace Database\Seeders;

use App\Models\film;
use App\Models\harga;
use App\Models\jadwal;
use App\Models\studio;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'meysa',
            'email' => 'meysa@example.com',
            'password' => bcrypt('1203456'),
            'nohp' => '09600',
            'role' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'reisa',
            'email' => 'reisa@example.com',
            'password' => bcrypt('1234156'),
            'nohp' => '09200',
            'role' => 'user',
        ]);

        User::factory()->create([
            'name' => 'Alhan',
            'email' => 'Alhan@example.com',
            'password' => bcrypt('123456'),
            'nohp' => '0900',
            'role' => 'owner',
        ]);

        User::factory()->create([
            'name' => 'rintan',
            'email' => 'rintan@example.com',
            'password' => bcrypt('1234567'),
            'nohp' => '09100',
            'role' => 'kasir',
        ]);

        studio::create([
            'nama' => 'studio',
        ]);

        film::create([
            'studio_id' => 1,
            'judul' => 'jangan panggil ibu kafir',
            'genre' => 'horror',
            'durasi' => 185,
            'deskripsi' => 'gatau soalnya alhan belum nonton',
            'status' => 'upcomming',
            'tanggalmulai' =>  null,
            'tanggalselesai' => null,
            'poster' => null,
        ]);

        harga::create([
            'jenis_hari' => 'weekday',
            'harga' => 1000,
        ]);

        jadwal::create([
            'studio_id' => 1,
            'film_id' => 1,
            'harga_id' => 1,
            'tanggal' => '2025-11-09',
            'jamtayang' => 10.20,
        ]);
    }
}
