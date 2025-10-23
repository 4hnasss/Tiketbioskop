<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Film;
use App\Models\Studio;
use App\Models\Harga;
use App\Models\Jadwal;
use App\Models\Tiket;
use App\Models\Kursi;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ==== USER ====
        $user = User::factory()->create([
            'name' => 'meysa',
            'email' => 'meysa@example.com',
            'password' => bcrypt('1203456'),
            'nohp' => '09600',
            'role' => 'admin',
        ]);

        $user = User::factory()->create([
            'name' => 'alhan',
            'email' => 'alhan@example.com',
            'password' => bcrypt('1234567'),
            'nohp' => '09600',
            'role' => 'user',
        ]);

        // ==== FILM ====
        $film1 = Film::create([
            'judul' => 'Rumah Untuk Alie',
            'genre' => 'Drama',
            'durasi' => 185,
            'deskripsi' => 'Rumah untuk Alie adalah film drama keluarga Indonesia tahun 2025 ...',
            'status' => 'upcomming',
            'tanggalmulai' => '2025-10-23',
            'tanggalselesai' => '2025-10-24',
            'poster' => 'img/Alie.jpg',
        ]);

        $film2 = Film::create([
            'judul' => 'Home Sweet Loan',
            'genre' => 'Drama',
            'durasi' => 185,
            'deskripsi' => 'Home Sweet Loan adalah film drama keluarga Indonesia tahun 2024 yang disutradarai oleh Sabrina Rochelle Kalangie dan dibintangi oleh Yunita Siregar, Derby Romero dan Fita Anggriani. Film ini diadaptasi dari novel berjudul sama karya Almira Bastari dan diproduksi oleh Visinema Pictures. Ceritanya berfokus pada Kaluna (Yunita Siregar), seorang pekerja kantoran dari keluarga sederhana yang bermimpi memiliki rumah sendiri. Sebagai anak bungsu, ia tinggal bersama orang tua, kakak-kakaknya yang sudah berkeluarga, dan keponakan, yang membuat rumahnya terasa ramai dan sering mengganggu kenyamanannya.',
            'status' => 'playnow',
            'tanggalmulai' => '2025-10-22',
            'tanggalselesai' => '2025-11-21',
            'poster' => 'img/home.jpg',
        ]);

        $film3 = Film::create([
            'judul' => 'Pangepungan Di Bukit Duri',
            'genre' => 'Horror',
            'durasi' => 185,
            'deskripsi' => 'Pengepungan di Bukit Duri adalah film laga menegangkan Indonesia tahun 2025 ...',
            'status' => 'playnow',
            'tanggalmulai' => '2025-10-20',
            'tanggalselesai' => '2025-12-11',
            'poster' => 'img/pangepungan.jpg',
        ]);

        $film4 = Film::create([
            'judul' => 'Pangepungan Di Bukit Duri',
            'genre' => 'Horror',
            'durasi' => 185,
            'deskripsi' => 'Pengepungan di Bukit Duri adalah film laga menegangkan Indonesia tahun 2025 ...',
            'status' => 'playnow',
            'tanggalmulai' => '2025-11-20',
            'tanggalselesai' => '2025-11-21',
            'poster' => 'img/pangepungan.jpg',
        ]);

        // ==== STUDIO ====
        $studio1 = Studio::create(['nama_studio' => 'Studio 1']);
        $studio2 = Studio::create(['nama_studio' => 'Studio 2']);
        $studio3 = Studio::create(['nama_studio' => 'Studio 3']);

        // ==== HARGA ====
        $hargaWeekday = Harga::create(['jenis_hari' => 'weekday', 'harga' => 1]);
        $hargaWeekend = Harga::create(['jenis_hari' => 'weekend', 'harga' => 1500]);

        // ==== JADWAL ====
        $jadwal = Jadwal::create([
            'film_id' => $film1->id,
            'harga_id' => $hargaWeekday->id,
            'studio_id' => $studio1->id,
            'tanggal' => '2025-10-23',
            'jamtayang' => '10:00:00',
        ]);

        $jadwal = Jadwal::create([
            'film_id' => $film2->id,
            'harga_id' => $hargaWeekend->id,
            'studio_id' => $studio2->id,
            'tanggal' => '2025-10-22',
            'jamtayang' => '12:00:00',
        ]);

        $jadwal = Jadwal::create([
            'film_id' => $film2->id,
            'harga_id' => $hargaWeekend->id,
            'studio_id' => $studio2->id,
            'tanggal' => '2025-10-22',
            'jamtayang' => '16:00:00',
        ]);

        $jadwal = Jadwal::create([
            'film_id' => $film2->id,
            'harga_id' => $hargaWeekend->id,
            'studio_id' => $studio2->id,
            'tanggal' => '2025-10-22',
            'jamtayang' => '18:30:00',
        ]);

        $jadwal = Jadwal::create([
            'film_id' => $film3->id,
            'harga_id' => $hargaWeekday->id,
            'studio_id' => $studio3->id,
            'tanggal' => '2025-12-20',
            'jamtayang' => '12:00:00',
        ]);

        $jadwal = Jadwal::create([
            'film_id' => $film4->id,
            'harga_id' => $hargaWeekday->id,
            'studio_id' => $studio3->id,
            'tanggal' => '2025-12-20',
            'jamtayang' => '12:00:00',
        ]);

    }
}
