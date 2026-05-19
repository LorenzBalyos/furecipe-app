<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Sign In | Furecipe</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-[#FDF7F2] text-stone-900 flex items-center justify-center min-h-screen p-4">

    <div class="w-full max-w-sm bg-white p-8 rounded-[2.5rem] border border-stone-200/60 shadow-xl">
        <div class="text-center mb-6">
            <img src="https://www.svgrepo.com/show/355037/google.svg" class="w-10 h-10 mx-auto mb-2">
            <span class="text-xl font-black text-stone-800 tracking-tight">Sign In with Google</span>
            <p class="text-stone-400 text-xs mt-1">Enter your linked Gmail to fetch profile authorization parameters.</p>
        </div>

        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-xl text-[11px] font-bold text-red-600">
                ⚠️ {{ $errors->first() }}
            </div>
        @endif

        <form action="/login/google/verify-account" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-[10px] font-black uppercase text-stone-400 mb-1">Google Email Address</label>
                <input type="email" name="email" required placeholder="username@gmail.com" class="w-full bg-stone-50 border border-stone-200 rounded-xl px-4 py-3 text-sm focus:outline-orange-500">
            </div>
            <button type="submit" class="w-full bg-stone-900 text-white font-black text-xs uppercase tracking-widest py-4 rounded-xl shadow-md pt-3 hover:bg-stone-800 transition">
                Send Google Verification OTP
            </button>
        </form>
    </div>

</body>
</html>
