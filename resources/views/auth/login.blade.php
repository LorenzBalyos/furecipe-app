<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In | Furecipe</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-[#FDF7F2] text-stone-900 flex items-center justify-center min-h-screen p-4">

    <div class="w-full max-w-sm bg-white p-8 rounded-[2.5rem] border border-stone-200/60 shadow-xl">
        <div class="text-center mb-6">
            <span class="text-2xl font-black text-orange-600 tracking-tighter">FURECIPE🐾</span>
            <p class="text-stone-400 text-xs mt-1">Sign in to your pet parent console</p>
        </div>

        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-xl">
                <ul class="list-none p-0 m-0">
                    @foreach ($errors->all() as $error)
                        <li class="text-[11px] font-bold text-red-600 flex items-center gap-1.5">
                            ⚠️ {{ $error }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="/login" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-[10px] font-black uppercase text-stone-400 mb-1">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="name@domain.com" class="w-full bg-stone-50 border border-stone-200 rounded-xl px-4 py-3 text-sm focus:outline-orange-500">
            </div>
            <div>
                <label class="block text-[10px] font-black uppercase text-stone-400 mb-1">Password</label>
                <input type="password" name="password" required placeholder="••••••••" class="w-full bg-stone-50 border border-stone-200 rounded-xl px-4 py-3 text-sm focus:outline-orange-500">
            </div>
            <button type="submit" class="w-full bg-stone-900 text-white font-black text-xs uppercase tracking-widest py-4 rounded-xl shadow-md pt-3 hover:bg-stone-800 transition">
                Sign In
            </button>
        </form>

        <div class="relative flex py-4 items-center">
            <div class="grow border-t border-stone-200"></div>
            <span class="shrink mx-4 text-stone-400 text-[10px] font-bold uppercase">Or</span>
            <div class="grow border-t border-stone-200"></div>
        </div>

        <a href="/login/google" class="w-full bg-white border border-stone-200 text-stone-700 font-bold text-xs py-3.5 rounded-xl shadow-xs flex items-center justify-center gap-2 no-underline hover:bg-stone-50 transition">
            <img src="https://www.svgrepo.com/show/355037/google.svg" class="w-4 h-4">
            Continue with Google
        </a>

        <p class="text-center text-xs text-stone-400 mt-6">New parent? <a href="/register" class="text-orange-600 font-bold hover:underline">Create an account</a></p>
    </div>

</body>
</html>
