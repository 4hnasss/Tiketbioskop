<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flixora | Home</title>
    @vite('resources/css/app.css')
</head>
<body>
    <div class="bg-gradient-to-b from-white to-blue-100 min-h-screen flex flex-col">
    @include('components.navbar')
        <div class="w-full h-full">
            <p class="ml-[300px] text-[35px] text-black font-bold">
                Lagi tayang
            </p>
        </div>
    </div>
    @vite('resources/js/app.js')
    @include('components.footer')
</body>
</html>
