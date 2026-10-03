<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Kebab Fetih</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col justify-center items-center px-4 py-8 bg-gradient-to-br from-orange-500 to-red-700">
            <div class="text-center text-white mb-6">
                <div class="text-xs tracking-[0.3em] opacity-80">SISTEM MEMBER &amp; KASIR</div>
                <div class="text-3xl font-extrabold mt-1">KEBAB FETIH</div>
            </div>

            <div class="w-full sm:max-w-md px-6 py-6 bg-white shadow-xl rounded-2xl">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
