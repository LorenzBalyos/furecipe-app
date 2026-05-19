<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account | Furecipe</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-[#FDF7F2] text-stone-900 flex items-center justify-center min-h-screen p-4">

    <div class="w-full max-w-sm bg-white p-8 rounded-[2.5rem] border border-stone-200/60 shadow-xl">
        <div class="text-center mb-6">
            <span class="text-2xl font-black text-orange-600 tracking-tighter">FURECIPE🐾</span>
            <p class="text-stone-400 text-xs mt-1">Register your platform credentials</p>
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

        <form action="/register" method="POST" class="space-y-4" id="registerForm">
            @csrf
            <div>
                <label class="block text-[10px] font-black uppercase text-stone-400 mb-1">Full Name</label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="Cassandra Perez" class="w-full bg-stone-50 border border-stone-200 rounded-xl px-4 py-3 text-sm focus:outline-orange-500">
            </div>
            <div>
                <label class="block text-[10px] font-black uppercase text-stone-400 mb-1">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="cassy@domain.com" class="w-full bg-stone-50 border border-stone-200 rounded-xl px-4 py-3 text-sm focus:outline-orange-500">
            </div>
            <div>
                <label class="block text-[10px] font-black uppercase text-stone-400 mb-1">Password</label>
                <input type="password" name="password" id="passwordInput" required placeholder="••••••••" class="w-full bg-stone-50 border border-stone-200 rounded-xl px-4 py-3 text-sm focus:outline-orange-500">

                <div class="mt-2 p-2.5 bg-stone-50 rounded-xl border border-stone-100 space-y-1">
                    <p class="text-[9px] font-black uppercase text-stone-400 tracking-wider mb-1">Security Parameters:</p>
                    <div id="rule-length" class="text-[10px] font-bold text-stone-400 flex items-center gap-1">❌ Min. 8 characters</div>
                    <div id="rule-mixed" class="text-[10px] font-bold text-stone-400 flex items-center gap-1">❌ Letters & numbers</div>
                    <div id="rule-special" class="text-[10px] font-bold text-stone-400 flex items-center gap-1">❌ At least 1 symbol (@$!%*?&)</div>
                </div>
            </div>
            <button type="submit" class="w-full bg-stone-900 text-white font-black text-xs uppercase tracking-widest py-4 rounded-xl shadow-md pt-3 hover:bg-stone-800 transition">
                Register Account
            </button>
        </form>

        <p class="text-center text-xs text-stone-400 mt-6">Already have an account? <a href="/login" class="text-orange-600 font-bold hover:underline">Sign In instead</a></p>
    </div>

    <script>
        const passInput = document.getElementById('passwordInput');
        const ruleLength = document.getElementById('rule-length');
        const ruleMixed = document.getElementById('rule-mixed');
        const ruleSpecial = document.getElementById('rule-special');

        passInput.addEventListener('input', function() {
            const val = passInput.value;

            // Validate Length
            if(val.length >= 8) {
                ruleLength.innerHTML = "✅ Min. 8 characters";
                ruleLength.className = "text-[10px] font-bold text-emerald-600";
            } else {
                ruleLength.innerHTML = "❌ Min. 8 characters";
                ruleLength.className = "text-[10px] font-bold text-stone-400";
            }

            // Validate Letters & Numbers Mix
            if(/[A-Za-z]/.test(val) && /[0-9]/.test(val)) {
                ruleMixed.innerHTML = "✅ Letters & numbers";
                ruleMixed.className = "text-[10px] font-bold text-emerald-600";
            } else {
                ruleMixed.innerHTML = "❌ Letters & numbers";
                ruleMixed.className = "text-[10px] font-bold text-stone-400";
            }

            // Validate Special Symbol Parameters
            if(/[@$!%*?&#\-_.:+=]/.test(val)) {
                ruleSpecial.innerHTML = "✅ At least 1 symbol";
                ruleSpecial.className = "text-[10px] font-bold text-emerald-600";
            } else {
                ruleSpecial.innerHTML = "❌ At least 1 symbol (@$!%*?&)";
                ruleSpecial.className = "text-[10px] font-bold text-stone-400";
            }
        });
    </script>
</body>
</html>
