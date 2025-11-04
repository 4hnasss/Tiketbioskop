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
        $admin = User::factory()->create([
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

        $kasir = User::factory()->create([
            'name' => 'rintan',
            'email' => 'rintan@example.com',
            'password' => bcrypt('1234567'),
            'nohp' => '096009000',
            'role' => 'kasir',
        ]);

        // ==== FILM ====
        $film1 = Film::create([
            'judul' => 'Rumah Untuk Alie',
            'genre' => 'Drama',
            'durasi' => 185,
            'trailer' => 'rumah.mp4',
            'deskripsi' => 'Rumah untuk Alie adalah film drama keluarga Indonesia tahun 2025 yang disutradarai oleh Herwin Novianto berdasarkan novel berjudul sama karya Lenn Liu. Film produksi Falcon Pictures ini dibintangi oleh Anantya Kirana, Rizky Hanggono, dan Dito Darmawan. Rumah untuk Alie tayang perdana di bioskop pada tanggal 17 April 2025.',
            'status' => 'playnow',
            'tanggalmulai' => '2025-10-30',
            'tanggalselesai' => '2025-11-30',
            'poster' => 'Alie.jpg',
        ]);

        $film2 = Film::create([
            'judul' => 'Home Sweet Loan',
            'genre' => 'Drama',
            'durasi' => 115,
            'trailer' => 'home.mp4',
            'deskripsi' => 'Home Sweet Loan adalah film drama keluarga Indonesia tahun 2024 yang disutradarai oleh Sabrina Rochelle Kalangie dan dibintangi oleh Yunita Siregar, Derby Romero dan Fita Anggriani. Film ini diadaptasi dari novel berjudul sama karya Almira Bastari dan diproduksi oleh Visinema Pictures. Ceritanya berfokus pada Kaluna (Yunita Siregar), seorang pekerja kantoran dari keluarga sederhana yang bermimpi memiliki rumah sendiri.',
            'status' => 'playnow',
            'tanggalmulai' => '2025-10-30',
            'tanggalselesai' => '2025-11-30',
            'poster' => 'home.jpg',
        ]);

        $film3 = Film::create([
            'judul' => 'Pangepungan Di Bukit Duri',
            'genre' => 'action',
            'durasi' => 185,
            'trailer' => 'pangepungan.mp4',
            'deskripsi' => 'Pengepungan di Bukit Duri adalah film laga menegangkan Indonesia tahun 2025 yang ditulis dan disutradarai oleh Joko Anwar. Film produksi Amazon MGM Studios serta Come and See Pictures ini dibintangi oleh Morgan Oey, Omara Esteghlal, dan Hana Malasan. Film ini berkisah mengenai Edwin, seorang guru pengganti yang ditugaskan mengajar di SMA Duri, sebuah sekolah dengan reputasi buruk sebagai tempat berkumpulnya siswa bermasalah.',
            'status' => 'playnow',
            'tanggalmulai' => '2025-10-30',
            'tanggalselesai' => '2025-11-30',
            'poster' => 'pangepungan.jpg',
        ]);

        $film4 = Film::create([
            'judul' => 'Avatar',
            'genre' => 'action',
            'durasi' => 185,
            'trailer' => 'avatar.mp4',
            'deskripsi' => 'Avatar: Fire and Ash adalah sebuah film fiksi ilmiah epos Amerika tahun 2025 yang disutradarai, ditulis, diproduksi bersama, dan diedit oleh James Cameron. Dan merupakan film ketiga dalam waralaba Avatar, serta sekuel dari Avatar: The Way of Water (2022).',
            'status' => 'playnow',
            'tanggalmulai' => '2025-10-30',
            'tanggalselesai' => '2025-11-30',
            'poster' => 'avatar.jpeg',
        ]);

        $film5 = Film::create([
            'judul' => 'Jangan Panggil Reisa Kafir',
            'genre' => 'Drama',
            'durasi' => 185,
            'trailer' => 'janganpanggil.mp4',
            'deskripsi' => 'Jangan Panggil Mama Kafir adalah sebuah film drama Indonesia tahun 2025 yang disutradarai oleh Dyan Sunu Prastowo. Film tersebut dibintangi oleh Michelle Ziudith, Giorgino Abraham, Emmie Lemu, TJ Ruth, Prastiwi Dwiarti, Dira Sugandi, N Humaira Jahra, Kaneishia Yusuf, Runny Rudiyanti dan Gilbert Pattiruhu. Film tersebut dirilis pada 16 Oktober 2025.',
            'status' => 'playnow',
            'tanggalmulai' => '2025-10-30',
            'tanggalselesai' => '2025-11-30',
            'poster' => 'jangan.jpeg',
        ]);

        $film6 = Film::create([
            'judul' => 'Andai Ibu Tidak Menikah Dengan Ayah',
            'genre' => 'Drama',
            'durasi' => 185,
            'trailer' => 'andaiibu.mp4',
            'deskripsi' => 'Andai Ibu Tidak Menikah dengan Ayah adalah sebuah film drama keluarga Indonesia tahun 2025 yang disutradarai oleh Kuntz Agus. Film tersebut dibintangi oleh Amanda Rawles, Sha Ine Febriyanti, Bucek Depp, Eva Celia, Nayla Purnama dan Indian Akbar. Film tersebut dirilis pada 4 September 2025.',
            'status' => 'upcomming',
            'tanggalmulai' => '2025-10-30',
            'tanggalselesai' => '2025-11-10',
            'poster' => 'andaiibu.jpeg',
        ]);

        $film7 = Film::create([
            'judul' => 'Jumbo',
            'genre' => 'Kartun',
            'durasi' => 185,
            'trailer' => 'jumbo.mp4',
            'deskripsi' => 'Film Jumbo mengisahkan seorang anak yatim piatu berusia 10 tahun bernama Don. Ia sering diremehkan karena memiliki tubuh yang besar. Don mempunyai sebuah buku dongeng warisan orang tuanya, yang penuh ilustrasi dan cerita ajaib.',
            'status' => 'upcomming',
            'tanggalmulai' => '2025-11-02',
            'tanggalselesai' => '2025-11-10',
            'poster' => 'jumbo.jpg',
        ]);

        $film8 = Film::create([
            'judul' => 'Panggil Aku Ayah',
            'genre' => 'Drama',
            'durasi' => 185,
            'trailer' => 'panggilaku.mp4',
            'deskripsi' => 'Intan adalah gadis kecil yang tumbuh jauh dari hidup sempurna, hanya punya satu hal paling berharga, ibunya, Rossa. Namun hidup membawa mereka ke titik terendah. Terjebak utang, Rossa didatangi dua penagih yakni Dedi dan Tatang.',
            'status' => 'upcomming',
            'tanggalmulai' => '2025-11-02',
            'tanggalselesai' => '2025-11-10',
            'poster' => 'panggil.jpeg',
        ]);

        $film9 = Film::create([
            'judul' => 'Ngeri-Ngeri Sedap',
            'genre' => 'Drama',
            'durasi' => 185,
            'trailer' => 'ngeri.mp4',
            'deskripsi' => 'Ngeri-Ngeri Sedap (bahasa Inggris: Missing Home) adalah film drama komedi Indonesia tahun 2022 yang disutradarai dan ditulis oleh Bene Dion Rajagukguk. Film berlatar Suku Batak ini dibintangi oleh Arswendy Beningswara Nasution, Tika Panggabean, Boris Bokir Manullang, Gita Bhebhita Butarbutar, Lolox, dan Indra Jegel.',
            'status' => 'upcomming',
            'tanggalmulai' => '2025-11-02',
            'tanggalselesai' => '2025-11-10',
            'poster' => 'ngeri.jpg',
        ]);

        $film10 = Film::create([
            'judul' => 'Bertaut Rindu',
            'genre' => 'Drama',
            'durasi' => 185,
            'trailer' => 'bertaut.mp4',
            'deskripsi' => 'Kehidupan Jovanka berubah setelah perceraian orang tuanya. Bersama dengan Yuli, ibunya ia pindah ke Bandung dan memulai semuanya dari awal. Di sekolah baru, ia bertemu dengan Magnus, cowok pendiam yang menyimpan banyak rahasia dan trauma.',
            'status' => 'upcomming',
            'tanggalmulai' => '2025-11-02',
            'tanggalselesai' => '2025-11-10',
            'poster' => 'bertaut.jpeg',
        ]);

        // ==== STUDIO ====
        $studio1 = Studio::create(['nama_studio' => 'Studio 1']);
        $studio2 = Studio::create(['nama_studio' => 'Studio 2']);
        $studio3 = Studio::create(['nama_studio' => 'Studio 3']);

        // ==== HARGA ====
        $hargaWeekday = Harga::create(['jenis_hari' => 'weekday', 'harga' => 25000]);
        $hargaWeekend = Harga::create(['jenis_hari' => 'weekend', 'harga' => 30000]);

        // ==== DYNAMIC DATES ====
        $today = Carbon::today()->toDateString();
        $tomorrow = Carbon::tomorrow()->toDateString();
        $dayAfterTomorrow = Carbon::today()->addDays(2)->toDateString();
        $day3 = Carbon::today()->addDays(3)->toDateString();
        $day4 = Carbon::today()->addDays(4)->toDateString();
        $day5 = Carbon::today()->addDays(5)->toDateString();

        // Determine if each day is weekday or weekend
        $todayIsWeekend = Carbon::today()->isWeekend();
        $tomorrowIsWeekend = Carbon::tomorrow()->isWeekend();
        $day2IsWeekend = Carbon::today()->addDays(2)->isWeekend();
        $day3IsWeekend = Carbon::today()->addDays(3)->isWeekend();
        $day4IsWeekend = Carbon::today()->addDays(4)->isWeekend();
        $day5IsWeekend = Carbon::today()->addDays(5)->isWeekend();

        // ==== JADWAL ====
        // HARI INI (Day 0)
        
        // STUDIO 1 - Film 1 (Rumah Untuk Alie)
        Jadwal::create([
            'film_id' => $film1->id,
            'harga_id' => $todayIsWeekend ? $hargaWeekend->id : $hargaWeekday->id,
            'studio_id' => $studio1->id,
            'tanggal' => $today,
            'jamtayang' => '10:00:00',
        ]);

        Jadwal::create([
            'film_id' => $film1->id,
            'harga_id' => $todayIsWeekend ? $hargaWeekend->id : $hargaWeekday->id,
            'studio_id' => $studio1->id,
            'tanggal' => $today,
            'jamtayang' => '14:00:00',
        ]);

        Jadwal::create([
            'film_id' => $film1->id,
            'harga_id' => $todayIsWeekend ? $hargaWeekend->id : $hargaWeekday->id,
            'studio_id' => $studio1->id,
            'tanggal' => $today,
            'jamtayang' => '18:00:00',
        ]);

        // STUDIO 2 - Film 2 (Home Sweet Loan)
        Jadwal::create([
            'film_id' => $film2->id,
            'harga_id' => $todayIsWeekend ? $hargaWeekend->id : $hargaWeekday->id,
            'studio_id' => $studio2->id,
            'tanggal' => $today,
            'jamtayang' => '10:00:00',
        ]);

        Jadwal::create([
            'film_id' => $film2->id,
            'harga_id' => $todayIsWeekend ? $hargaWeekend->id : $hargaWeekday->id,
            'studio_id' => $studio2->id,
            'tanggal' => $today,
            'jamtayang' => '14:00:00',
        ]);

        Jadwal::create([
            'film_id' => $film2->id,
            'harga_id' => $todayIsWeekend ? $hargaWeekend->id : $hargaWeekday->id,
            'studio_id' => $studio2->id,
            'tanggal' => $today,
            'jamtayang' => '18:00:00',
        ]);

        // STUDIO 3 - Film 3 (Pangepungan Di Bukit Duri)
        Jadwal::create([
            'film_id' => $film3->id,
            'harga_id' => $todayIsWeekend ? $hargaWeekend->id : $hargaWeekday->id,
            'studio_id' => $studio3->id,
            'tanggal' => $today,
            'jamtayang' => '10:00:00',
        ]);

        Jadwal::create([
            'film_id' => $film3->id,
            'harga_id' => $todayIsWeekend ? $hargaWeekend->id : $hargaWeekday->id,
            'studio_id' => $studio3->id,
            'tanggal' => $today,
            'jamtayang' => '14:00:00',
        ]);

        Jadwal::create([
            'film_id' => $film3->id,
            'harga_id' => $todayIsWeekend ? $hargaWeekend->id : $hargaWeekday->id,
            'studio_id' => $studio3->id,
            'tanggal' => $today,
            'jamtayang' => '18:00:00',
        ]);

        // BESOK (Day 1)
        
        // STUDIO 1 - Film 4 (Avatar)
        Jadwal::create([
            'film_id' => $film4->id,
            'harga_id' => $tomorrowIsWeekend ? $hargaWeekend->id : $hargaWeekday->id,
            'studio_id' => $studio1->id,
            'tanggal' => $tomorrow,
            'jamtayang' => '10:00:00',
        ]);

        Jadwal::create([
            'film_id' => $film4->id,
            'harga_id' => $tomorrowIsWeekend ? $hargaWeekend->id : $hargaWeekday->id,
            'studio_id' => $studio1->id,
            'tanggal' => $tomorrow,
            'jamtayang' => '14:00:00',
        ]);

        Jadwal::create([
            'film_id' => $film4->id,
            'harga_id' => $tomorrowIsWeekend ? $hargaWeekend->id : $hargaWeekday->id,
            'studio_id' => $studio1->id,
            'tanggal' => $tomorrow,
            'jamtayang' => '18:00:00',
        ]);

        // STUDIO 2 - Film 5 (Jangan Panggil Reisa Kafir)
        Jadwal::create([
            'film_id' => $film5->id,
            'harga_id' => $tomorrowIsWeekend ? $hargaWeekend->id : $hargaWeekday->id,
            'studio_id' => $studio2->id,
            'tanggal' => $tomorrow,
            'jamtayang' => '10:00:00',
        ]);

        Jadwal::create([
            'film_id' => $film5->id,
            'harga_id' => $tomorrowIsWeekend ? $hargaWeekend->id : $hargaWeekday->id,
            'studio_id' => $studio2->id,
            'tanggal' => $tomorrow,
            'jamtayang' => '14:00:00',
        ]);

        Jadwal::create([
            'film_id' => $film5->id,
            'harga_id' => $tomorrowIsWeekend ? $hargaWeekend->id : $hargaWeekday->id,
            'studio_id' => $studio2->id,
            'tanggal' => $tomorrow,
            'jamtayang' => '18:00:00',
        ]);

        // STUDIO 3 - Film 9 (Ngeri-Ngeri Sedap)
        Jadwal::create([
            'film_id' => $film9->id,
            'harga_id' => $tomorrowIsWeekend ? $hargaWeekend->id : $hargaWeekday->id,
            'studio_id' => $studio3->id,
            'tanggal' => $tomorrow,
            'jamtayang' => '10:00:00',
        ]);

        Jadwal::create([
            'film_id' => $film9->id,
            'harga_id' => $tomorrowIsWeekend ? $hargaWeekend->id : $hargaWeekday->id,
            'studio_id' => $studio3->id,
            'tanggal' => $tomorrow,
            'jamtayang' => '14:00:00',
        ]);

        Jadwal::create([
            'film_id' => $film9->id,
            'harga_id' => $tomorrowIsWeekend ? $hargaWeekend->id : $hargaWeekday->id,
            'studio_id' => $studio3->id,
            'tanggal' => $tomorrow,
            'jamtayang' => '18:00:00',
        ]);

        // LUSA (Day 2)
        
        // STUDIO 1 - Film 6 (Andai Ibu Tidak Menikah Dengan Ayah)
        Jadwal::create([
            'film_id' => $film6->id,
            'harga_id' => $day2IsWeekend ? $hargaWeekend->id : $hargaWeekday->id,
            'studio_id' => $studio1->id,
            'tanggal' => $dayAfterTomorrow,
            'jamtayang' => '10:00:00',
        ]);

        Jadwal::create([
            'film_id' => $film6->id,
            'harga_id' => $day2IsWeekend ? $hargaWeekend->id : $hargaWeekday->id,
            'studio_id' => $studio1->id,
            'tanggal' => $dayAfterTomorrow,
            'jamtayang' => '14:00:00',
        ]);

        Jadwal::create([
            'film_id' => $film6->id,
            'harga_id' => $day2IsWeekend ? $hargaWeekend->id : $hargaWeekday->id,
            'studio_id' => $studio1->id,
            'tanggal' => $dayAfterTomorrow,
            'jamtayang' => '18:00:00',
        ]);

        // STUDIO 2 - Film 7 (Jumbo)
        Jadwal::create([
            'film_id' => $film7->id,
            'harga_id' => $day2IsWeekend ? $hargaWeekend->id : $hargaWeekday->id,
            'studio_id' => $studio2->id,
            'tanggal' => $dayAfterTomorrow,
            'jamtayang' => '10:00:00',
        ]);

        Jadwal::create([
            'film_id' => $film7->id,
            'harga_id' => $day2IsWeekend ? $hargaWeekend->id : $hargaWeekday->id,
            'studio_id' => $studio2->id,
            'tanggal' => $dayAfterTomorrow,
            'jamtayang' => '14:00:00',
        ]);

        Jadwal::create([
            'film_id' => $film7->id,
            'harga_id' => $day2IsWeekend ? $hargaWeekend->id : $hargaWeekday->id,
            'studio_id' => $studio2->id,
            'tanggal' => $dayAfterTomorrow,
            'jamtayang' => '18:00:00',
        ]);

        // STUDIO 3 - Film 10 (Bertaut Rindu)
        Jadwal::create([
            'film_id' => $film10->id,
            'harga_id' => $day2IsWeekend ? $hargaWeekend->id : $hargaWeekday->id,
            'studio_id' => $studio3->id,
            'tanggal' => $dayAfterTomorrow,
            'jamtayang' => '10:00:00',
        ]);

        Jadwal::create([
            'film_id' => $film10->id,
            'harga_id' => $day2IsWeekend ? $hargaWeekend->id : $hargaWeekday->id,
            'studio_id' => $studio3->id,
            'tanggal' => $dayAfterTomorrow,
            'jamtayang' => '14:00:00',
        ]);

        Jadwal::create([
            'film_id' => $film10->id,
            'harga_id' => $day2IsWeekend ? $hargaWeekend->id : $hargaWeekday->id,
            'studio_id' => $studio3->id,
            'tanggal' => $dayAfterTomorrow,
            'jamtayang' => '18:00:00',
        ]);

        // DAY 3
        
        // STUDIO 1 - Film 8 (Panggil Aku Ayah)
        Jadwal::create([
            'film_id' => $film8->id,
            'harga_id' => $day3IsWeekend ? $hargaWeekend->id : $hargaWeekday->id,
            'studio_id' => $studio1->id,
            'tanggal' => $day3,
            'jamtayang' => '10:00:00',
        ]);

        Jadwal::create([
            'film_id' => $film8->id,
            'harga_id' => $day3IsWeekend ? $hargaWeekend->id : $hargaWeekday->id,
            'studio_id' => $studio1->id,
            'tanggal' => $day3,
            'jamtayang' => '14:00:00',
        ]);

        Jadwal::create([
            'film_id' => $film8->id,
            'harga_id' => $day3IsWeekend ? $hargaWeekend->id : $hargaWeekday->id,
            'studio_id' => $studio1->id,
            'tanggal' => $day3,
            'jamtayang' => '18:00:00',
        ]);

        // STUDIO 2 - Film 1 (Rumah Untuk Alie)
        Jadwal::create([
            'film_id' => $film1->id,
            'harga_id' => $day3IsWeekend ? $hargaWeekend->id : $hargaWeekday->id,
            'studio_id' => $studio2->id,
            'tanggal' => $day3,
            'jamtayang' => '10:00:00',
        ]);

        Jadwal::create([
            'film_id' => $film1->id,
            'harga_id' => $day3IsWeekend ? $hargaWeekend->id : $hargaWeekday->id,
            'studio_id' => $studio2->id,
            'tanggal' => $day3,
            'jamtayang' => '14:00:00',
        ]);

        Jadwal::create([
            'film_id' => $film1->id,
            'harga_id' => $day3IsWeekend ? $hargaWeekend->id : $hargaWeekday->id,
            'studio_id' => $studio2->id,
            'tanggal' => $day3,
            'jamtayang' => '18:00:00',
        ]);

        // STUDIO 3 - Film 4 (Avatar)
        Jadwal::create([
            'film_id' => $film4->id,
            'harga_id' => $day3IsWeekend ? $hargaWeekend->id : $hargaWeekday->id,
            'studio_id' => $studio3->id,
            'tanggal' => $day3,
            'jamtayang' => '10:00:00',
        ]);

        Jadwal::create([
            'film_id' => $film4->id,
            'harga_id' => $day3IsWeekend ? $hargaWeekend->id : $hargaWeekday->id,
            'studio_id' => $studio3->id,
            'tanggal' => $day3,
            'jamtayang' => '14:00:00',
        ]);

        Jadwal::create([
            'film_id' => $film4->id,
            'harga_id' => $day3IsWeekend ? $hargaWeekend->id : $hargaWeekday->id,
            'studio_id' => $studio3->id,
            'tanggal' => $day3,
            'jamtayang' => '18:00:00',
        ]);

        // DAY 4
        
        // STUDIO 1 - Film 2 (Home Sweet Loan)
        Jadwal::create([
            'film_id' => $film2->id,
            'harga_id' => $day4IsWeekend ? $hargaWeekend->id : $hargaWeekday->id,
            'studio_id' => $studio1->id,
            'tanggal' => $day4,
            'jamtayang' => '10:00:00',
        ]);

        Jadwal::create([
            'film_id' => $film2->id,
            'harga_id' => $day4IsWeekend ? $hargaWeekend->id : $hargaWeekday->id,
            'studio_id' => $studio1->id,
            'tanggal' => $day4,
            'jamtayang' => '14:00:00',
        ]);

        Jadwal::create([
            'film_id' => $film2->id,
            'harga_id' => $day4IsWeekend ? $hargaWeekend->id : $hargaWeekday->id,
            'studio_id' => $studio1->id,
            'tanggal' => $day4,
            'jamtayang' => '18:00:00',
        ]);

        // STUDIO 2 - Film 3 (Pangepungan Di Bukit Duri)
        Jadwal::create([
            'film_id' => $film3->id,
            'harga_id' => $day4IsWeekend ? $hargaWeekend->id : $hargaWeekday->id,
            'studio_id' => $studio2->id,
            'tanggal' => $day4,
            'jamtayang' => '10:00:00',
        ]);

        Jadwal::create([
            'film_id' => $film3->id,
            'harga_id' => $day4IsWeekend ? $hargaWeekend->id : $hargaWeekday->id,
            'studio_id' => $studio2->id,
            'tanggal' => $day4,
            'jamtayang' => '14:00:00',
        ]);

        Jadwal::create([
            'film_id' => $film3->id,
            'harga_id' => $day4IsWeekend ? $hargaWeekend->id : $hargaWeekday->id,
            'studio_id' => $studio2->id,
            'tanggal' => $day4,
            'jamtayang' => '18:00:00',

        ]);

        // STUDIO 3 - Film 5 (Jangan Panggil Reisa Kafir)
        Jadwal::create([
            'film_id' => $film5->id,
            'harga_id' => $day4IsWeekend ? $hargaWeekend->id : $hargaWeekday->id,
            'studio_id' => $studio3->id,
            'tanggal' => $day4,
            'jamtayang' => '10:00:00',
        ]);

        Jadwal::create([
            'film_id' => $film5->id,
            'harga_id' => $day4IsWeekend ? $hargaWeekend->id : $hargaWeekday->id,
            'studio_id' => $studio3->id,
            'tanggal' => $day4,
            'jamtayang' => '14:00:00',
        ]);

        Jadwal::create([
            'film_id' => $film5->id,
            'harga_id' => $day4IsWeekend ? $hargaWeekend->id : $hargaWeekday->id,
            'studio_id' => $studio3->id,
            'tanggal' => $day4,
            'jamtayang' => '18:00:00',
        ]);

        // DAY 5
        
        // STUDIO 1 - Film 9 (Ngeri-Ngeri Sedap)
        Jadwal::create([
            'film_id' => $film9->id,
            'harga_id' => $day5IsWeekend ? $hargaWeekend->id : $hargaWeekday->id,
            'studio_id' => $studio1->id,
            'tanggal' => $day5,
            'jamtayang' => '10:00:00',
        ]);

        Jadwal::create([
            'film_id' => $film9->id,
            'harga_id' => $day5IsWeekend ? $hargaWeekend->id : $hargaWeekday->id,
            'studio_id' => $studio1->id,
            'tanggal' => $day5,
            'jamtayang' => '14:00:00',
        ]);

        Jadwal::create([
            'film_id' => $film9->id,
            'harga_id' => $day5IsWeekend ? $hargaWeekend->id : $hargaWeekday->id,
            'studio_id' => $studio1->id,
            'tanggal' => $day5,
            'jamtayang' => '18:00:00',
        ]);

        // STUDIO 2 - Film 6 (Andai Ibu Tidak Menikah Dengan Ayah)
        Jadwal::create([
            'film_id' => $film6->id,
            'harga_id' => $day5IsWeekend ? $hargaWeekend->id : $hargaWeekday->id,
            'studio_id' => $studio2->id,
            'tanggal' => $day5,
            'jamtayang' => '10:00:00',
        ]);

        Jadwal::create([
            'film_id' => $film6->id,
            'harga_id' => $day5IsWeekend ? $hargaWeekend->id : $hargaWeekday->id,
            'studio_id' => $studio2->id,
            'tanggal' => $day5,
            'jamtayang' => '14:00:00',
        ]);

        Jadwal::create([
            'film_id' => $film6->id,
            'harga_id' => $day5IsWeekend ? $hargaWeekend->id : $hargaWeekday->id,
            'studio_id' => $studio2->id,
            'tanggal' => $day5,
            'jamtayang' => '18:00:00',
        ]);

        // STUDIO 3 - Film 7 (Jumbo)
        Jadwal::create([
            'film_id' => $film7->id,
            'harga_id' => $day5IsWeekend ? $hargaWeekend->id : $hargaWeekday->id,
            'studio_id' => $studio3->id,
            'tanggal' => $day5,
            'jamtayang' => '10:00:00',
        ]);

        Jadwal::create([
            'film_id' => $film7->id,
            'harga_id' => $day5IsWeekend ? $hargaWeekend->id : $hargaWeekday->id,
            'studio_id' => $studio3->id,
            'tanggal' => $day5,
            'jamtayang' => '14:00:00',
        ]);

        Jadwal::create([
            'film_id' => $film7->id,
            'harga_id' => $day5IsWeekend ? $hargaWeekend->id : $hargaWeekday->id,
            'studio_id' => $studio3->id,
            'tanggal' => $day5,
            'jamtayang' => '18:00:00',
        ]);

        $this->command->info('Database seeded successfully!');
        $this->command->info('Total Jadwal: ' . Jadwal::count());
        $this->command->info('Total Kursi: ' . Kursi::count());
    }
}