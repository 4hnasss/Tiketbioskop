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
            'trailer' => 'rumah.mp4',
            'deskripsi' => 'Rumah untuk Alie adalah film drama keluarga Indonesia tahun 2025 yang disutradarai oleh Herwin Novianto berdasarkan novel berjudul sama karya Lenn Liu. Film produksi Falcon Pictures ini dibintangi oleh Anantya Kirana, Rizky Hanggono, dan Dito Darmawan. Rumah untuk Alie tayang perdana di bioskop pada tanggal 17 April 2025.',
            'status' => 'playnow',
            'tanggalmulai' => '2025-10-21',
            'tanggalselesai' => '2025-10-30',
            'poster' => 'Alie.jpg',
        ]);

        $film2 = Film::create([
            'judul' => 'Home Sweet Loan',
            'genre' => 'Drama',
            'durasi' => 115,
            'trailer' => 'home.mp4',
            'deskripsi' => 'Home Sweet Loan adalah film drama keluarga Indonesia tahun 2024 yang disutradarai oleh Sabrina Rochelle Kalangie dan dibintangi oleh Yunita Siregar, Derby Romero dan Fita Anggriani. Film ini diadaptasi dari novel berjudul sama karya Almira Bastari dan diproduksi oleh Visinema Pictures. Ceritanya berfokus pada Kaluna (Yunita Siregar), seorang pekerja kantoran dari keluarga sederhana yang bermimpi memiliki rumah sendiri. Sebagai anak bungsu, ia tinggal bersama orang tua, kakak-kakaknya yang sudah berkeluarga, dan keponakan, yang membuat rumahnya terasa ramai dan sering mengganggu kenyamanannya.',
            'status' => 'playnow',
            'tanggalmulai' => '2025-10-22',
            'tanggalselesai' => '2025-10-30',
            'poster' => 'home.jpg',
        ]);

        $film3 = Film::create([
            'judul' => 'Pangepungan Di Bukit Duri',
            'genre' => 'action',
            'durasi' => 185,
            'trailer' => 'pangepungan.mp4',
            'deskripsi' => 'Pengepungan di Bukit Duri adalah film laga menegangkan Indonesia tahun 2025 yang ditulis dan disutradarai oleh Joko Anwar. Film produksi Amazon MGM Studios serta Come and See Pictures ini dibintangi oleh Morgan Oey, Omara Esteghlal, dan Hana Malasan. Film ini berkisah mengenai Edwin, seorang guru pengganti yang ditugaskan mengajar di SMA Duri, sebuah sekolah dengan reputasi buruk sebagai tempat berkumpulnya siswa bermasalah.',
            'status' => 'playnow',
            'tanggalmulai' => '2025-10-23',
            'tanggalselesai' => '2025-10-30',
            'poster' => 'pangepungan.jpg',
        ]);

        $film4 = Film::create([
            'judul' => 'Avatar',
            'genre' => 'action',
            'durasi' => 185,
            'trailer' => 'avatar.mp4',
            'deskripsi' => 'Avatar: Fire and Ash adalah sebuah film fiksi ilmiah epos Amerika tahun 2025 yang disutradarai, ditulis, diproduksi bersama, dan diedit oleh James Cameron.[2] Dan merupakan film ketiga dalam waralaba Avatar, serta sekuel dari Avatar: The Way of Water (2022). Cameron memproduksinya dengan Jon Landau. Cameron, Rick Jaffa, Amanda Silver, Josh Friedman dan Shane Salerno terlibat dalam proses penulisan. Pemeran yang terdiri dari Sam Worthington, Zoe Saldaña, Sigourney Weaver, Stephen Lang, Joel David Moore, CCH Pounder, Giovanni Ribisi, Dileep Rao, Matt Gerald, Kate Winslet, Cliff Curtis, Edie Falco, Brendan Cowell, Jemaine Clement, Britain Dalton, Trinity Jo-Li Bliss, Jack Champion, Bailey Bass dan Filip Geljo mengulangi peran mereka dari film sebelumnya, dengan Michelle Yeoh, David Thewlis, dan Oona Chaplin memerankan karakter baru. Cameron menyatakan bahwa Avatar: The Seed Bearer sedang dipertimbangkan sebagai kemungkinan judul utama.',
            'status' => 'playnow',
            'tanggalmulai' => '2025-10-24',
            'tanggalselesai' => '2025-10-30',
            'poster' => 'avatar.jpeg',
        ]);

        $film5 = Film::create([
            'judul' => 'Jangan Panggil Reisa Kafir',
            'genre' => 'Drama',
            'durasi' => 185,
            'trailer' => 'janganpanggil.mp4',
            'deskripsi' => 'Jangan Panggil Mama Kafir adalah sebuah film drama Indonesia tahun 2025 yang disutradarai oleh Dyan Sunu Prastowo. Film tersebut dibintangi oleh Michelle Ziudith, Giorgino Abraham, Emmie Lemu, TJ Ruth, Prastiwi Dwiarti, Dira Sugandi, N Humaira Jahra, Kaneishia Yusuf, Runny Rudiyanti dan Gilbert Pattiruhu. Film tersebut dirilis pada 16 Oktober 2025.',
            'status' => 'playnow',
            'tanggalmulai' => '2025-10-25',
            'tanggalselesai' => '2025-10-30',
            'poster' => 'jangan.jpeg',
        ]);

        $film6 = Film::create([
            'judul' => 'Andai Ibu Tidak Menikah Dengan Ayah',
            'genre' => 'Drama',
            'durasi' => 185,
            'trailer' => 'andaiibu.mp4',
            'deskripsi' => 'Andai Ibu Tidak Menikah dengan Ayah adalah sebuah film drama keluarga Indonesia tahun 2025 yang disutradarai oleh Kuntz Agus. Film tersebut dibintangi oleh Amanda Rawles, Sha Ine Febriyanti, Bucek Depp, Eva Celia, Nayla Purnama dan Indian Akbar. Film tersebut dirilis pada 4 September 2025.',
            'status' => 'upcomming',
            'tanggalmulai' => '2025-11-02',
            'tanggalselesai' => '2025-11-10',
            'poster' => 'andaiibu.jpeg',
        ]);

        $film7 = Film::create([
            'judul' => 'Jumbo',
            'genre' => 'Kartun',
            'durasi' => 185,
            'trailer' => 'jumbo.mp4',
            'deskripsi' => 'Film Jumbo mengisahkan seorang anak yatim piatu berusia 10 tahun bernama Don. Ia sering diremehkan karena memiliki tubuh yang besar. Don mempunyai sebuah buku dongeng warisan orang tuanya, yang penuh ilustrasi dan cerita ajaib. Buku tersebut bukan hanya kenang-kenangan, tetapi juga menjadi sumber inspirasi dan pelarian bagi Don dari dunia yang terasa tidak ramah karena kerap diremehkan oleh teman-temannya.',
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
            'deskripsi' => 'Intan adalah gadis kecil yang tumbuh jauh dari hidup sempurna, hanya punya satu hal paling berharga, ibunya, Rossa. Namun hidup membawa mereka ke titik terendah. Terjebak utang, Rossa didatangi dua penagih yakni Dedi dan Tatang. Rossa kemudian terdesak oleh Dedi dan Tatang hingga akhirnya menjadikan Intan sebagai jaminan hutangnya. Dedi yang awalnya hanya melihat Intan sebagai jaminan, perlahan justru terikat dalam kisah hidup gadis kecil ini. Dari pertemuan yang tak sengaja, tumbuh ikatan yang tak terduga.',
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
            'deskripsi' => 'Ngeri-Ngeri Sedap (bahasa Inggris: Missing Home) adalah film drama komedi Indonesia tahun 2022 yang disutradarai dan ditulis oleh Bene Dion Rajagukguk. Film berlatar Suku Batak ini dibintangi oleh Arswendy Beningswara Nasution, Tika Panggabean, Boris Bokir Manullang, Gita Bhebhita Butarbutar, Lolox, dan Indra Jegel. Film ini ditayangkan di bioskop Indonesia pada 2 Juni 2022.[1] Meskipun memiliki judul yang sama dengan buku yang ditulis oleh Bene Dion, film ini tidak diadaptasi dari buku tersebut.',
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
            'deskripsi' => 'Kehidupan Jovanka berubah setelah perceraian orang tuanya. Bersama dengan Yuli, ibunya ia pindah ke Bandung dan memulai semuanya dari awal. Di sekolah baru, ia bertemu dengan Magnus, cowok pendiam yang menyimpan banyak rahasia dan trauma. Pertemuan ini tak hanya mempertemukan dua hati, tapi dua jiwa yang sama-sama terluka.',
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

        // ==== JADWAL ====
        $jadwal = Jadwal::create([
            'film_id' => $film1->id,
            'harga_id' => $hargaWeekday->id,
            'studio_id' => $studio1->id,
            'tanggal' => '2025-10-27',
            'jamtayang' => '10:00:00',
        ]);

        $jadwal = Jadwal::create([
            'film_id' => $film1->id,
            'harga_id' => $hargaWeekday->id,
            'studio_id' => $studio1->id,
            'tanggal' => '2025-10-27',
            'jamtayang' => '14:00:00',
        ]);

        $jadwal = Jadwal::create([
            'film_id' => $film1->id,
            'harga_id' => $hargaWeekday->id,
            'studio_id' => $studio1->id,
            'tanggal' => '2025-10-27',
            'jamtayang' => '18:00:00',
        ]);

        $jadwal = Jadwal::create([
            'film_id' => $film2->id,
            'harga_id' => $hargaWeekday->id,
            'studio_id' => $studio1->id,
            'tanggal' => '2025-10-27',
            'jamtayang' => '10:00:00',
        ]);

        $jadwal = Jadwal::create([
            'film_id' => $film2->id,
            'harga_id' => $hargaWeekday->id,
            'studio_id' => $studio1->id,
            'tanggal' => '2025-10-27',
            'jamtayang' => '14:00:00',
        ]);

        $jadwal = Jadwal::create([
            'film_id' => $film2->id,
            'harga_id' => $hargaWeekday->id,
            'studio_id' => $studio1->id,
            'tanggal' => '2025-10-27',
            'jamtayang' => '18:00:00',
        ]);

        $jadwal = Jadwal::create([
            'film_id' => $film3->id,
            'harga_id' => $hargaWeekday->id,
            'studio_id' => $studio1->id,
            'tanggal' => '2025-10-27',
            'jamtayang' => '10:00:00',
        ]);

        $jadwal = Jadwal::create([
            'film_id' => $film3->id,
            'harga_id' => $hargaWeekday->id,
            'studio_id' => $studio1->id,
            'tanggal' => '2025-10-27',
            'jamtayang' => '14:00:00',
        ]);

        $jadwal = Jadwal::create([
            'film_id' => $film3->id,
            'harga_id' => $hargaWeekday->id,
            'studio_id' => $studio1->id,
            'tanggal' => '2025-10-27',
            'jamtayang' => '18:00:00',
        ]);

        $jadwal = Jadwal::create([
            'film_id' => $film4->id,
            'harga_id' => $hargaWeekday->id,
            'studio_id' => $studio1->id,
            'tanggal' => '2025-10-27',
            'jamtayang' => '10:00:00',
        ]);

        $jadwal = Jadwal::create([
            'film_id' => $film4->id,
            'harga_id' => $hargaWeekday->id,
            'studio_id' => $studio1->id,
            'tanggal' => '2025-10-27',
            'jamtayang' => '14:00:00',
        ]);

        $jadwal = Jadwal::create([
            'film_id' => $film4->id,
            'harga_id' => $hargaWeekday->id,
            'studio_id' => $studio1->id,
            'tanggal' => '2025-10-27',
            'jamtayang' => '18:00:00',
        ]);

        $jadwal = Jadwal::create([
            'film_id' => $film5->id,
            'harga_id' => $hargaWeekday->id,
            'studio_id' => $studio1->id,
            'tanggal' => '2025-10-27',
            'jamtayang' => '10:00:00',
        ]);

        $jadwal = Jadwal::create([
            'film_id' => $film5->id,
            'harga_id' => $hargaWeekday->id,
            'studio_id' => $studio1->id,
            'tanggal' => '2025-10-27',
            'jamtayang' => '14:00:00',
        ]);

        $jadwal = Jadwal::create([
            'film_id' => $film5->id,
            'harga_id' => $hargaWeekday->id,
            'studio_id' => $studio1->id,
            'tanggal' => '2025-10-27',
            'jamtayang' => '18:00:00',
        ]);

        // ==== JADWAL ====
        $jadwal = Jadwal::create([
            'film_id' => $film1->id,
            'harga_id' => $hargaWeekday->id,
            'studio_id' => $studio1->id,
            'tanggal' => '2025-10-28',
            'jamtayang' => '10:00:00',
        ]);

        $jadwal = Jadwal::create([
            'film_id' => $film1->id,
            'harga_id' => $hargaWeekday->id,
            'studio_id' => $studio1->id,
            'tanggal' => '2025-10-28',
            'jamtayang' => '14:00:00',
        ]);

        $jadwal = Jadwal::create([
            'film_id' => $film1->id,
            'harga_id' => $hargaWeekday->id,
            'studio_id' => $studio1->id,
            'tanggal' => '2025-10-28',
            'jamtayang' => '18:00:00',
        ]);

        $jadwal = Jadwal::create([
            'film_id' => $film2->id,
            'harga_id' => $hargaWeekday->id,
            'studio_id' => $studio1->id,
            'tanggal' => '2025-10-28',
            'jamtayang' => '10:00:00',
        ]);

        $jadwal = Jadwal::create([
            'film_id' => $film2->id,
            'harga_id' => $hargaWeekday->id,
            'studio_id' => $studio1->id,
            'tanggal' => '2025-10-28',
            'jamtayang' => '14:00:00',
        ]);

        $jadwal = Jadwal::create([
            'film_id' => $film2->id,
            'harga_id' => $hargaWeekday->id,
            'studio_id' => $studio1->id,
            'tanggal' => '2025-10-28',
            'jamtayang' => '18:00:00',
        ]);

        $jadwal = Jadwal::create([
            'film_id' => $film3->id,
            'harga_id' => $hargaWeekday->id,
            'studio_id' => $studio1->id,
            'tanggal' => '2025-10-28',
            'jamtayang' => '10:00:00',
        ]);

        $jadwal = Jadwal::create([
            'film_id' => $film3->id,
            'harga_id' => $hargaWeekday->id,
            'studio_id' => $studio1->id,
            'tanggal' => '2025-10-28',
            'jamtayang' => '14:00:00',
        ]);

        $jadwal = Jadwal::create([
            'film_id' => $film3->id,
            'harga_id' => $hargaWeekday->id,
            'studio_id' => $studio1->id,
            'tanggal' => '2025-10-28',
            'jamtayang' => '18:00:00',
        ]);

        $jadwal = Jadwal::create([
            'film_id' => $film4->id,
            'harga_id' => $hargaWeekday->id,
            'studio_id' => $studio1->id,
            'tanggal' => '2025-10-28',
            'jamtayang' => '10:00:00',
        ]);

        $jadwal = Jadwal::create([
            'film_id' => $film4->id,
            'harga_id' => $hargaWeekday->id,
            'studio_id' => $studio1->id,
            'tanggal' => '2025-10-28',
            'jamtayang' => '14:00:00',
        ]);

        $jadwal = Jadwal::create([
            'film_id' => $film4->id,
            'harga_id' => $hargaWeekday->id,
            'studio_id' => $studio1->id,
            'tanggal' => '2025-10-28',
            'jamtayang' => '18:00:00',
        ]);

        $jadwal = Jadwal::create([
            'film_id' => $film5->id,
            'harga_id' => $hargaWeekday->id,
            'studio_id' => $studio1->id,
            'tanggal' => '2025-10-28',
            'jamtayang' => '10:00:00',
        ]);

        $jadwal = Jadwal::create([
            'film_id' => $film5->id,
            'harga_id' => $hargaWeekday->id,
            'studio_id' => $studio1->id,
            'tanggal' => '2025-10-28',
            'jamtayang' => '14:00:00',
        ]);

        $jadwal = Jadwal::create([
            'film_id' => $film5->id,
            'harga_id' => $hargaWeekday->id,
            'studio_id' => $studio1->id,
            'tanggal' => '2025-10-28',
            'jamtayang' => '18:00:00',
        ]);
    }
}
