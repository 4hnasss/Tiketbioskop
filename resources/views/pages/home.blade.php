<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flixora | Home</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gradient-to-b from-white to-blue-100 min-h-screen flex flex-col">

    {{-- Include Navbar --}}
    @include('components.navbar')

    <div class="ml-[141px] w-full h-full">
        <p class="text-[35px] text-black font-bold">
            Lagi tayang
        </p>
    </div>
    

    @vite('resources/js/app.js')
    @include('components.footer')
</body>
</html>
