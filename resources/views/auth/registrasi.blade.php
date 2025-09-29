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
    <div class="w-[480px] h-[600px] bg-[#14274E] rounded-[10px] flex flex-col items-center">
        <p class="text-[30px] text-[#D6E4F0] font-semibold text-center pt-[30px] ">
            Create your account
        </p>
        <p class="text-[10px] text-[#D6E4F0] font-extralight text-center pt-[5px] mx-auto block mb-[40px]">
            welcome to flixora, a comfortable place to watch movies
        </p>

        <form class="flex flex-col ">
        <!--Nama-->
            <label class="text-[13px] text-left font-medium mb-[1px] text-[#D6E4F0]">Full Name</label>
            <input 
                type="text" 
                class="w-[340px] h-[40px] bg-white text-[#394867] ] px-3 rounded-[10px] mb-[10px]" 
                placeholder="Enter your full name" 
            >

        <!--Email-->
            <label class="text-[13px] text-left font-medium mb-[1px] text-[#D6E4F0]">Email address</label>
            <input 
                type="email" 
                class="w-[340px] h-[40px] bg-white text-[#394867] px-3 rounded-[10px] mb-[10px]" 
                placeholder="email address" 
            >

        <!--Password-->
            <label class="text-[13px] text-left font-medium mb-[1px] text-[#D6E4F0]">Password</label>
            <input 
                type="password" 
                class="w-[340px] h-[40px] bg-white text-[#394867] px-3 rounded-[10px] mb-[10px]" 
                placeholder="Password" 
            >

        <!--Nohp-->
            <label class="text-[13px] text-left font-medium mb-[1px] text-[#D6E4F0]">Phone number</label>
            <input 
                type="tel" 
                class="w-[340px] h-[40px] bg-white text-[#394867] px-3 rounded-[10px] mb-[10px]" 
                placeholder="Phone number" 
            >

        <!--foto-->
            <label class="text-[13px] text-left font-medium mb-[1px] text-[#D6E4F0]">Profile</label>
            <input 
                type="file" 
                class="w-[340px] h-[40px] bg-white text-[#394867] flex items-center justify-center px-3 pt-1.5 rounded-[10px] file:mr-4 file:py-1 file:px-3 file:rounded-[8px] file:border-0 file:text-sm file:font-semibold file:bg-[#1E56A0] file:text-white hover:file:bg-[#163d75] mb-[10px]"
            >


            <button type="submit" href="/login" class="w-[340px] h-[40px] bg-[#1E56A0]  text-white text-center mt-[25px] px-3 rounded-[10px] cursor-pointer font-semibold">
                Register
            </button>
        </form>
    </div>
    </div>
</body>
</html>