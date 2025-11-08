<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profil Pengguna | Flixora</title>
  @vite('resources/css/app.css')
  <script src="https://unpkg.com/lucide@latest"></script>
  <style>
    /* Animasi fade-in untuk elemen */
    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
      }
      to {
        opacity: 1;
      }
    }

    @keyframes scaleIn {
      from {
        opacity: 0;
        transform: scale(0.9);
      }
      to {
        opacity: 1;
        transform: scale(1);
      }
    }

    /* Kelas untuk animasi */
    .animate-on-load {
      opacity: 0;
      animation: fadeInUp 0.8s ease-out forwards;
    }

    .animate-fade-in {
      opacity: 0;
      animation: fadeIn 0.6s ease-out forwards;
    }

    .animate-scale-in {
      opacity: 0;
      animation: scaleIn 0.6s ease-out forwards;
    }

    /* Delay untuk animasi bertahap */
    .delay-100 { animation-delay: 0.1s; }
    .delay-200 { animation-delay: 0.2s; }
    .delay-300 { animation-delay: 0.3s; }
    .delay-400 { animation-delay: 0.4s; }
    .delay-500 { animation-delay: 0.5s; }
    .delay-600 { animation-delay: 0.6s; }

    /* Animasi hover untuk tombol */
    .btn-hover-scale {
      transition: transform 0.3s ease, background-color 0.3s ease, box-shadow 0.3s ease;
    }
    .btn-hover-scale:hover {
      transform: scale(1.05);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    /* Animasi tambahan untuk tampilan lebih menarik */
    .animate-slide-in-left {
      opacity: 0;
      transform: translateX(-50px);
      animation: slideInLeft 0.8s ease-out forwards;
    }

    .animate-slide-in-right {
      opacity: 0;
      transform: translateX(50px);
      animation: slideInRight 0.8s ease-out forwards;
    }

    @keyframes slideInLeft {
      to {
        opacity: 1;
        transform: translateX(0);
      }
    }

    @keyframes slideInRight {
      to {
        opacity: 1;
        transform: translateX(0);
      }
    }
  </style>
</head>

<body class="bg-gradient-to-b from-[#e8f0ff] to-[#fdfdff] font-sans text-gray-800 min-h-screen">
  
  <!-- NAVBAR -->
  @include('components.navbar')

  <!-- MAIN CONTAINER -->
  <div class="max-w-5xl mx-auto mt-24 mb-24 bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden animate-on-load">
    
    <!-- HEADER -->
    <div class="flex flex-col sm:flex-row items-center justify-between px-10 py-10 bg-gradient-to-r from-[#4a90e2] to-[#357abd] text-white animate-fade-in delay-100">
      <!-- User Info -->
      <div class="flex items-center gap-5 animate-slide-in-left delay-200">
        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=ffffff&color=4a90e2&size=120"
             alt="User Avatar" 
             class="w-24 h-24 rounded-full border-4 border-white shadow-md animate-scale-in delay-300">
        <div class="animate-fade-in delay-400">
          <h2 class="text-3xl font-bold">{{ $user->name }}</h2>
          <p class="text-sm opacity-90">{{ $user->email }}</p>
        </div>
      </div>

      <!-- Home Button -->
      <div class="mt-6 sm:mt-0 animate-slide-in-right delay-500">
        <a href="{{ route('home') }}" 
           class="btn-hover-scale inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-white text-[#4a90e2] font-semibold hover:bg-gray-100 transition">
          <i data-lucide="home" class="w-5 h-5"></i> Beranda
        </a>
      </div>
    </div>

    <!-- TABS -->
    <div class="flex justify-center border-b border-gray-200 bg-[#f9fbff] animate-fade-in delay-600">
      <button class="px-8 py-4 text-[#4a90e2] border-b-2 border-[#4a90e2] font-semibold text-sm tracking-wide">
        Profil Pengguna
      </button>
    </div>

    <!-- PROFILE CONTENT -->
    <div class="p-10 grid md:grid-cols-2 gap-8">
      
      <!-- ACCOUNT INFORMATION -->
      <div class="space-y-5 animate-slide-in-left delay-100">
        <h3 class="text-lg font-semibold text-gray-700 mb-2">Informasi Akun</h3>

        <!-- Full Name -->
        <div class="animate-fade-in delay-200">
          <label class="block text-sm text-gray-600 mb-1">Nama Lengkap</label>
          <input type="text" value="{{ $user->name }}" readonly
                 class="w-full bg-[#f9fbff] border border-gray-200 rounded-xl px-4 py-2.5 text-gray-700 shadow-sm focus:ring-2 focus:ring-[#4a90e2]">
        </div>

        <!-- Email -->
        <div class="animate-fade-in delay-300">
          <label class="block text-sm text-gray-600 mb-1">Email</label>
          <input type="email" value="{{ $user->email }}" readonly
                 class="w-full bg-[#f9fbff] border border-gray-200 rounded-xl px-4 py-2.5 text-gray-700 shadow-sm focus:ring-2 focus:ring-[#4a90e2]">
        </div>

        <!-- Phone Number -->
        <div class="animate-fade-in delay-400">
          <label class="block text-sm text-gray-600 mb-1">Nomor HP</label>
          <input type="text" value="{{ $user->nohp }}" readonly
                 class="w-full bg-[#f9fbff] border border-gray-200 rounded-xl px-4 py-2.5 text-gray-700 shadow-sm focus:ring-2 focus:ring-[#4a90e2]">
        </div>
      </div>

      <!-- ACCOUNT ACTIVITY -->
      <div class="bg-[#f9fbff] border border-gray-100 rounded-2xl p-6 flex flex-col justify-between shadow-inner animate-slide-in-right delay-200">
        <!-- Activity Info -->
        <div class="animate-fade-in delay-300">
          <h3 class="text-lg font-semibold text-gray-700 mb-3">Aktivitas Akun</h3>
          <p class="text-sm text-gray-600 leading-relaxed">
            Terakhir login: 
            <span class="font-medium text-gray-800">{{ $user->last_login ?? 'Baru login' }}</span><br>
            Tanggal bergabung: 
            <span class="font-medium text-gray-800">{{ $user->created_at->format('d M Y') }}</span>
          </p>
        </div>

        <!-- Transaction History Button -->
        <div class="mt-6 animate-fade-in delay-400">
          <a href="{{ route('transaksi.riwayat') }}" 
             class="btn-hover-scale inline-flex items-center justify-center gap-2 w-full px-5 py-3 bg-[#4a90e2] text-white font-medium rounded-xl hover:bg-[#357abd] transition">
            <i data-lucide="ticket" class="w-5 h-5"></i> Lihat Riwayat Pemesanan
          </a>
        </div>
      </div>
    </div>

    <!-- FOOTER ACTIONS -->
    <div class="px-10 py-6 bg-[#f9fbff] border-t border-gray-100 flex justify-end animate-fade-in delay-500">
      <a href="{{ route('home') }}" 
         class="btn-hover-scale px-6 py-2.5 rounded-lg bg-[#4a90e2] text-white font-medium hover:bg-[#357abd] transition flex items-center gap-2">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Beranda
      </a>
    </div>
  </div>

  <!-- FOOTER -->
  @include('components.footer')

  <!-- INITIALIZE LUCIDE ICONS -->
  <script>
    lucide.createIcons();
  </script>
</body>
</html>
