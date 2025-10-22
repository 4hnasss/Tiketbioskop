<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account - Cignifi</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-[#f4f7ff] flex items-center justify-center min-h-screen font-sans">

    <div class="w-full max-w-lg bg-white rounded-xl shadow-sm p-12 text-center"> 
        <!-- ↑ diperbesar: max-w-lg & p-12 -->

        <!-- Logo -->
        <div class="flex justify-center mb-3">
            <img src="img/Brand.png" alt="Cignifi" class="w-[80px] h-auto">
        </div>

        <!-- Title -->
        <h2 class="text-3xl font-semibold text-gray-800 mb-1">Create your account</h2>
        <p class="text-gray-500 text-sm mb-8">
            Already have an account?
            <a href="{{ route('login') }}" class="text-[#4a90e2] hover:underline font-medium">Log in</a>
        </p>

        <!-- Register Form -->
        <form method="POST" action="{{ route('register.store') }}" class="space-y-5 text-left">
            @csrf

            <!-- Full Name -->
            <div class="relative">
                <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A4 4 0 018 16h8a4 4 0 012.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </span>
                <input 
                    type="text" 
                    name="name" 
                    placeholder="Full name"
                    required
                    class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-lg text-gray-700 text-sm focus:ring-2 focus:ring-[#4a90e2] focus:border-transparent outline-none transition"
                >
            </div>

            <!-- Email -->
            <div class="relative">
                <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12H8m8 0a4 4 0 00-8 0m8 0v1a4 4 0 01-8 0v-1m0 0a4 4 0 018 0" />
                    </svg>
                </span>
                <input 
                    type="email" 
                    name="email" 
                    placeholder="Email address"
                    required
                    class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-lg text-gray-700 text-sm focus:ring-2 focus:ring-[#4a90e2] focus:border-transparent outline-none transition"
                >
            </div>

            <!-- Password -->
            <div class="relative">
                <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c.828 0 1.5-.672 1.5-1.5S12.828 8 12 8s-1.5.672-1.5 1.5S11.172 11 12 11z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.5 11a7.5 7.5 0 0115 0v4.5a3 3 0 01-3 3h-9a3 3 0 01-3-3V11z" />
                    </svg>
                </span>
                <input 
                    type="password" 
                    id="password"
                    name="password" 
                    placeholder="Password"
                    required
                    class="w-full pl-10 pr-10 py-3 border border-gray-200 rounded-lg text-gray-700 text-sm focus:ring-2 focus:ring-[#4a90e2] focus:border-transparent outline-none transition"
                >
                <!-- Eye Toggle -->
                <button type="button" 
                        id="togglePassword" 
                        class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-[#4a90e2] transition cursor-pointer">
                    <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 " fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>
            </div>

            <!-- Phone -->
            <div class="relative">
                <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a2 2 0 011.789 1.106l1.387 2.773a2 2 0 01-.45 2.316l-1.415 1.415a11.05 11.05 0 005.657 5.657l1.415-1.415a2 2 0 012.316-.45l2.773 1.387A2 2 0 0121 18.72V22a2 2 0 01-2 2h-1C9.163 24 0 14.837 0 3a2 2 0 012-2h1z" />
                    </svg>
                </span>
                <input 
                    type="tel" 
                    name="nohp" 
                    placeholder="Phone number"
                    class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-lg text-gray-700 text-sm focus:ring-2 focus:ring-[#4a90e2] focus:border-transparent outline-none transition"
                >
            </div>

            <!-- Button -->
            <button 
                type="submit" 
                class="w-full bg-[#4a90e2] text-white py-3 rounded-lg text-base font-medium hover:bg-[#357abd] transition cursor-pointer"
            >
                Register
            </button>
        </form>
    </div>

    <!-- Show/Hide Password Script -->
    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordField = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        togglePassword.addEventListener('click', () => {
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);

            // Toggle icon
            if (type === 'text') {
                eyeIcon.outerHTML = `
                    <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.955 9.955 0 013.093-4.362M9.88 9.88a3 3 0 104.24 4.24M3 3l18 18" />
                    </svg>`;
            } else {
                eyeIcon.outerHTML = `
                    <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>`;
            }
        });
    </script>

</body>
</html>
