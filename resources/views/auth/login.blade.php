<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Flixora</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-[#f4f7ff] flex items-center justify-center min-h-screen font-sans">

    <div class="w-full max-w-lg bg-white rounded-xl shadow-sm p-12 text-center"> 
        <!-- ↑ Lebih besar: max-w-lg dan padding p-12 -->

        <!-- Logo -->
        <div class="flex justify-center mb-3">
            <img src="/img/Brand.png" alt="Flixora" class="w-[80px] h-auto">
        </div>

        <!-- Title -->
        <h2 class="text-3xl font-semibold text-gray-800 mb-1">Welcome Back</h2>
        <p class="text-gray-500 text-sm mb-8">
            Don’t have an account yet? 
            <a href="{{ route('register') }}" class="text-[#4a90e2] hover:underline font-medium">Sign Up</a>
        </p>

        <!-- Login Form -->
        <form action="{{ route('login') }}" method="POST" class="space-y-5 text-left">
            @csrf

            <!-- Email Field -->
            <div class="relative">
                <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12H8m8 0a4 4 0 00-8 0m8 0v1a4 4 0 01-8 0v-1m0 0a4 4 0 018 0" />
                    </svg>
                </span>
                <input 
                    type="email" 
                    name="email"
                    placeholder="Enter your email"
                    required
                    class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-lg text-gray-700 text-sm focus:ring-2 focus:ring-[#4a90e2] focus:border-transparent outline-none transition"
                >
            </div>

            <!-- Password Field -->
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
                    placeholder="Enter your password"
                    required
                    class="w-full pl-10 pr-10 py-3 border border-gray-200 rounded-lg text-gray-700 text-sm focus:ring-2 focus:ring-[#4a90e2] focus:border-transparent outline-none transition"
                >
                <!-- Eye Toggle -->
                <button type="button" 
                        id="togglePassword" 
                        class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-[#4a90e2] transition cursor-pointer">
                    <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>
            </div>

            <!-- Button -->
            <button 
                type="submit"
                class="w-full bg-[#4a90e2] text-white py-3 rounded-lg text-base font-medium hover:bg-[#357abd] transition cursor-pointer"
            >
                Sign In
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

            // Toggle icon style (open/close eye)
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
