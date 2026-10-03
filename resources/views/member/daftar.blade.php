<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Member Kebab Fetih</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-gradient-to-br from-orange-500 to-red-700 py-8 px-4">
<div class="max-w-md mx-auto">
    <div class="text-center text-white mb-6">
        <div class="text-xs tracking-[0.3em] opacity-80">MEMBER</div>
        <div class="text-3xl font-extrabold mt-1">KEBAB FETIH</div>
        <p class="mt-2 text-sm opacity-90">Daftar gratis, dapat diskon 15% member baru dan kumpulin poin tiap beli.</p>
    </div>

    <form method="POST" action="{{ route('member.daftar.store') }}" class="bg-white rounded-2xl shadow-xl p-6 space-y-4">
        @csrf

        @if ($errors->any())
            <div class="p-3 bg-red-100 text-red-800 rounded text-sm">
                @foreach ($errors->all() as $e) <div>{{ $e }}</div> @endforeach
            </div>
        @endif

        <div>
            <label class="block font-medium mb-1 text-sm">Nama</label>
            <input type="text" name="name" value="{{ old('name') }}" required maxlength="100" class="w-full border-gray-300 rounded-lg focus:border-orange-500 focus:ring-orange-500">
        </div>
        <div>
            <label class="block font-medium mb-1 text-sm">Nomor HP / WhatsApp</label>
            <input type="tel" inputmode="numeric" name="phone" value="{{ old('phone') }}" required placeholder="08xxxxxxxxxx" class="w-full border-gray-300 rounded-lg focus:border-orange-500 focus:ring-orange-500">
        </div>
        <div>
            <label class="block font-medium mb-1 text-sm">Email (opsional)</label>
            <input type="email" name="email" value="{{ old('email') }}" class="w-full border-gray-300 rounded-lg focus:border-orange-500 focus:ring-orange-500">
        </div>
        <div>
            <label class="block font-medium mb-1 text-sm">Tanggal lahir (opsional, buat voucher ulang tahun)</label>
            <input type="date" name="birth_date" value="{{ old('birth_date') }}" class="w-full border-gray-300 rounded-lg focus:border-orange-500 focus:ring-orange-500">
        </div>

        {{-- honeypot anti-bot --}}
        <div style="position:absolute;left:-9999px" aria-hidden="true">
            <input type="text" name="website" tabindex="-1" autocomplete="off">
        </div>

        <button class="w-full py-3 bg-orange-600 hover:bg-orange-700 text-white font-semibold rounded-lg">Daftar Sekarang</button>
    </form>

    <div class="text-center mt-6">
        <a href="{{ route('kasir.index') }}" class="text-white/70 text-xs underline">Login kasir</a>
    </div>
</div>
</body>
</html>
