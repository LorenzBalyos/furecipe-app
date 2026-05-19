<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Google OTP | Furecipe</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-[#FDF7F2] text-stone-900 flex items-center justify-center min-h-screen p-4">

    <div class="w-full max-w-sm bg-white p-8 rounded-[2.5rem] border border-stone-200/60 shadow-xl">
        <div class="text-center mb-6">
            <span class="text-2xl font-black text-orange-600 tracking-tighter">GOOGLE OTP🔑</span>
            <p class="text-stone-400 text-xs mt-1">Please enter the 6-digit access code sent to your linked Google account.</p>
        </div>

        @if (session('error'))
            <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-xl text-[11px] font-bold text-red-600">
                ⚠️ {{ session('error') }}
            </div>
        @endif

        <form action="/login/google/otp-verify" method="POST" class="space-y-5">
            @csrf
            <div>
                <input type="text" name="otp" required maxlength="6" placeholder="000000"
                       class="w-full bg-stone-50 border border-stone-200 rounded-xl px-4 py-3 text-center text-xl font-black tracking-[1em] indent-[0.5em] focus:outline-orange-500">
            </div>

            <button type="submit" class="w-full bg-stone-900 text-white font-black text-xs uppercase tracking-widest py-4 rounded-xl shadow-md pt-3">
                Verify & Enter App
            </button>
        </form>
    </div>

</body>
</html>
