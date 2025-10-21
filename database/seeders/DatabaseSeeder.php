<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Film;
use App\Models\Studio;
use App\Models\Harga;
use App\Models\Jadwal;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ==== USER ====
        User::factory()->create([
            'name' => 'meysa',
            'email' => 'meysa@example.com',
            'password' => bcrypt('1203456'),
            'nohp' => '09600',
            'role' => 'admin',
        ]);

        // ==== FILM ====
        $film1 = Film::create([
            'judul' => 'Rumah Untuk Alie',
            'genre' => 'Drama',
            'durasi' => 185,
            'deskripsi' => 'Rumah untuk Alie adalah film drama keluarga Indonesia tahun 2025 yang disutradarai oleh Herwin Novianto berdasarkan novel berjudul sama karya Lenn Liu. Film produksi Falcon Pictures ini dibintangi oleh Anantya Kirana, Rizky Hanggono, dan Dito Darmawan. Rumah untuk Alie tayang perdana di bioskop pada tanggal 17 April 2025.',
            'status' => 'upcomming',
            'tanggalmulai' => '2025-10-20',
            'tanggalselesai' => '2025-10-21',
            'poster' => 'img/Alie.jpg',
        ]);

        $film2 = Film::create([
            'judul' => 'Home Sweet Loan',
            'genre' => 'Drama',
            'durasi' => 185,
            'deskripsi' => 'Home Sweet Loan adalah film drama keluarga Indonesia tahun 2024 yang disutradarai oleh Sabrina Rochelle Kalangie dan dibintangi oleh Yunita Siregar, Derby Romero dan Fita Anggriani. Film ini diadaptasi dari novel berjudul sama karya Almira Bastari dan diproduksi oleh Visinema Pictures. Ceritanya berfokus pada Kaluna (Yunita Siregar), seorang pekerja kantoran dari keluarga sederhana yang bermimpi memiliki rumah sendiri. Sebagai anak bungsu, ia tinggal bersama orang tua, kakak-kakaknya yang sudah berkeluarga, dan keponakan, yang membuat rumahnya terasa ramai dan sering mengganggu kenyamanannya.',
            'status' => 'playnow',
            'tanggalmulai' => '2025-10-20',
            'tanggalselesai' => '2025-10-21',
            'poster' => 'img/home.jpg',
        ]);

        $film3 = Film::create([
            'judul' => 'Pangepungan Di Bukit Duri',
            'genre' => 'Horror',
            'durasi' => 185,
            'deskripsi' => 'Pengepungan di Bukit Duri adalah film laga menegangkan Indonesia tahun 2025 yang ditulis dan disutradarai oleh Joko Anwar. Film produksi Amazon MGM Studios serta Come and See Pictures ini dibintangi oleh Morgan Oey, Omara Esteghlal, dan Hana Malasan. Film ini berkisah mengenai Edwin, seorang guru pengganti yang ditugaskan mengajar di SMA Duri, sebuah sekolah dengan reputasi buruk sebagai tempat berkumpulnya siswa bermasalah.',
            'status' => 'playnow',
            'tanggalmulai' => '2025-12-10',
            'tanggalselesai' => '2025-12-11',
            'poster' => 'img/pangepungan.jpg',
        ]);

        // ==== STUDIO ====
        $studio1 = Studio::create(['nama_studio' => 'Studio 1']);
        $studio2 = Studio::create(['nama_studio' => 'Studio 2']);
        $studio3 = Studio::create(['nama_studio' => 'Studio 3']);

        // ==== HARGA ====
        $hargaWeekday = Harga::create(['jenis_hari' => 'weekday', 'harga' => 20000]);
        $hargaWeekend = Harga::create(['jenis_hari' => 'weekend', 'harga' => 1500]);

        // ==== JADWAL ====
        // Kursi akan otomatis dibuat dari model Jadwal (lihat di bawah)
        Jadwal::create([
            'film_id' => $film1->id,
            'harga_id' => $hargaWeekday->id,
            'studio_id' => $studio1->id,
            'tanggal' => '2025-10-20',
            'jamtayang' => '10:00:00',
        ]);

        Jadwal::create([
            'film_id' => $film2->id,
            'harga_id' => $hargaWeekend->id,
            'studio_id' => $studio2->id,
            'tanggal' => '2025-12-10',
            'jamtayang' => '12:00:00',
        ]);

        Jadwal::create([
            'film_id' => $film2->id,
            'harga_id' => $hargaWeekend->id,
            'studio_id' => $studio2->id,
            'tanggal' => '2025-12-10',
            'jamtayang' => '16:00:00',
        ]);

        Jadwal::create([
            'film_id' => $film2->id,
            'harga_id' => $hargaWeekend->id,
            'studio_id' => $studio2->id,
            'tanggal' => '2025-12-10',
            'jamtayang' => '18:30:00',
        ]);

        Jadwal::create([
            'film_id' => $film3->id,
            'harga_id' => $hargaWeekday->id,
            'studio_id' => $studio3->id,
            'tanggal' => '2025-11-29',
            'jamtayang' => '12:00:00',
        ]);

        Jadwal::create([
            'film_id' => $film3->id,
            'harga_id' => $hargaWeekday->id,
            'studio_id' => $studio3->id,
            'tanggal' => '2025-11-29',
            'jamtayang' => '14:00:00',
        ]);
        
        Jadwal::create([
            'film_id' => $film3->id,
            'harga_id' => $hargaWeekday->id,
            'studio_id' => $studio3->id,
            'tanggal' => '2025-11-29',
            'jamtayang' => '17:30:00',
        ]);
    }
}
