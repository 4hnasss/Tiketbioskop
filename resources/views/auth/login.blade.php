<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gradient-to-b from-white to-blue-100 min-h-screen flex items-center justify-center flex-col">
    <img src="/img/Brand.png" alt="Flixora" class="w-[115px] h-[60px] absolute top-4 left-4">

    <div class="w-[480px] h-[373px] bg-[#14274E] rounded-[10px] flex flex-col items-center">
        <p class="text-[25px] text-[#D6E4F0] font-semibold text-center pt-[40px]">
            Welcome Back
        </p>
        <p class="text-[12px] text-[#D6E4F0] font-extralight text-center pt-[10px] mx-auto block">
            Don’t have an account yet? 
            <a href="/registrasi" class="font-semibold underline">
                Sign Up
            </a>
        </p>

        <form action="{{ route('login') }}" method="POST" class="flex flex-col items-center w-full mt-4">
            @csrf

            <input 
                type="email" 
                name="email"
                class="w-[340px] h-[40px] bg-white text-[#394867] mt-[35px] px-3 rounded-[10px]" 
                placeholder="Email address" 
                required
            >

            <input
                type="password" 
                name="password"
                class="w-[340px] h-[40px] bg-white text-[#394867] mt-[15px] px-3 rounded-[10px]" 
                placeholder="Password" 
                required
            >

            <button type="submit" class="w-[340px] h-[40px] bg-[#1E56A0] text-white text-center mt-[25px] px-3 rounded-[10px] cursor-pointer hover:bg-blue-800 transition">
                Login
            </button>
        </form>
    </div>
</body>
</html>
