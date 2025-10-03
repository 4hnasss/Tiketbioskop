<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    @vite('resources/css/app.css')
</head>
<body>
    <div class="bg-gradient-to-b from-white to-blue-100 min-h-screen flex items-center justify-center flex-col">
    <img src="/img/Brand.png" alt="Flixora" class="w-[115px] h-[60px] absolute top-4 left-4">
    <div class="w-fitt h-fitt bg-[#14274E] rounded-[10px] flex flex-col items-center pr-20 pl-20 pb-8">
        <p class="text-[30px] text-[#D6E4F0] font-semibold text-center pt-[15px] ">
            Create your account
        </p>
        <p class="text-[12px] text-[#D6E4F0] font-extralight text-center pt-[5px] mx-auto block mb-5">
            Already have an account?
            <a href="/login" class="font-semibold">
                Log in
            </a>
        </p>

        <form class="flex flex-col ">
        <!--Nama-->
            <label class="text-[13px] text-left font-medium mb-[1px] text-[#D6E4F0]">Full Name</label>
            <input 
                type="text" 
                class="w-[440px] h-[40px] bg-white text-[#394867] ] px-3 rounded-[10px] mb-[10px]" 
                placeholder="Enter your full name" 
            >

        <!--Email-->
            <label class="text-[13px] text-left font-medium mb-[1px] text-[#D6E4F0]">Email address</label>
            <input 
                type="email" 
                class="w-[440px] h-[40px] bg-white text-[#394867] px-3 rounded-[10px] mb-[10px]" 
                placeholder="email address" 
            >

        <!--Password-->
            <label class="text-[13px] text-left font-medium mb-[1px] text-[#D6E4F0]">Password</label>
            <input 
                type="password" 
                class="w-[440px] h-[40px] bg-white text-[#394867] px-3 rounded-[10px] mb-[10px]" 
                placeholder="Password" 
            >

        <!--Nohp-->
            <label class="text-[13px] text-left font-medium mb-[1px] text-[#D6E4F0]">Phone number</label>
            <input 
                type="tel" 
                class="w-[440px] h-[40px] bg-white text-[#394867] px-3 rounded-[10px] mb-5" 
                placeholder="Phone number" 
            >

            <button type="submit" href="/login" class="w-[440px] h-[40px] bg-[#1E56A0]  text-white text-center mt-[25px] px-3 rounded-[10px] cursor-pointer font-semibold">
                Register
            </button>
        </form>
    </div>
    </div>
</body>
</html>