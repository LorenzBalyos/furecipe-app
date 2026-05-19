<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Checkout | Furecipe</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-[#FDF7F2] text-stone-900 min-h-screen flex flex-col">

    <header class="bg-white border-b border-stone-100 p-4 sticky top-0 z-50">
        <div class="max-w-4xl mx-auto flex justify-between items-center">
            <span class="text-xs font-black tracking-tighter text-orange-600">FURECIPE🐾</span>
            <a href="/dashboard?tab=shop" class="text-xs text-stone-500 font-bold hover:underline">← Return to Shop</a>
        </div>
    </header>

    <main class="max-w-4xl mx-auto p-4 flex-1 w-full grid grid-cols-1 md:grid-cols-12 gap-6">

        <div class="md:col-span-7 bg-white p-6 rounded-2xl border border-stone-100 shadow-xs space-y-6">

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-xl text-xs space-y-1">
                    <p class="font-bold">⚠️ Fix the following fields to save successfully:</p>
                    <ul class="list-disc list-inside opacity-90">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-xl text-xs font-bold">
                    ❌ {{ session('error') }}
                </div>
            @endif

            <form id="secureOrderForm" action="/place-order" method="POST" class="space-y-6">
                @csrf
                <div>
                    <h3 class="font-black text-sm text-stone-800 mb-3 border-b pb-2">📋 1. Delivery Particulars</h3>
                    <div class="space-y-3 text-xs">
                        <div>
                            <label class="block text-[10px] font-bold uppercase text-stone-400 mb-1">Email Address</label>
                            <input type="email" name="shipping_email" required value="{{ old('shipping_email', session('user_email', '')) }}" class="w-full bg-stone-50 border border-stone-200 rounded-xl px-3 py-2.5 text-stone-900 focus:outline-none focus:border-orange-500">
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-[10px] font-bold uppercase text-stone-400 mb-1">First Name</label>
                                <input type="text" name="shipping_first_name" required value="{{ old('shipping_first_name', explode(' ', session('user_name', ''))[0] ?? '') }}" class="w-full bg-stone-50 border border-stone-200 rounded-xl px-3 py-2.5 text-stone-900 focus:outline-none focus:border-orange-500">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold uppercase text-stone-400 mb-1">Last Name</label>
                                <input type="text" name="shipping_last_name" required value="{{ old('shipping_last_name') }}" class="w-full bg-stone-50 border border-stone-200 rounded-xl px-3 py-2.5 text-stone-900 focus:outline-none focus:border-orange-500">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase text-stone-400 mb-1">Complete Delivery Address</label>
                            <textarea name="shipping_address" required rows="2" placeholder="Street, Barangay, Subd / Unit Number" class="w-full bg-stone-50 border border-stone-200 rounded-xl px-3 py-2.5 text-stone-900 resize-none focus:outline-none focus:border-orange-500">{{ old('shipping_address') }}</textarea>
                        </div>
                        <div class="grid grid-cols-3 gap-2">
                            <div>
                                <label class="block text-[10px] font-bold uppercase text-stone-400 mb-1">City</label>
                                <input type="text" name="shipping_city" required value="{{ old('shipping_city', 'Quezon City') }}" class="w-full bg-stone-50 border border-stone-200 rounded-xl px-3 py-2.5 text-stone-900 focus:outline-none focus:border-orange-500">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold uppercase text-stone-400 mb-1">Region</label>
                                <select name="shipping_region" required class="w-full bg-stone-50 border border-stone-200 rounded-xl px-3 py-2.5 text-stone-900 focus:outline-none focus:border-orange-500 h-[38px]">
                                    <option value="Metro Manila" {{ old('shipping_region') == 'Metro Manila' ? 'selected' : '' }}>Metro Manila</option>
                                    <option value="Central Luzon" {{ old('shipping_region') == 'Central Luzon' ? 'selected' : '' }}>Central Luzon</option>
                                    <option value="CALABARZON" {{ old('shipping_region') == 'CALABARZON' ? 'selected' : '' }}>CALABARZON</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold uppercase text-stone-400 mb-1">Postal Code</label>
                                <input type="text" name="shipping_postal" required value="{{ old('shipping_postal', '1101') }}" class="w-full bg-stone-50 border border-stone-200 rounded-xl px-3 py-2.5 text-stone-900 focus:outline-none focus:border-orange-500">
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="font-black text-sm text-stone-800 mb-3 border-b pb-2">💳 2. Payment Gateway Route</h3>
                    <div class="space-y-2 text-xs">

                        <label class="flex items-center justify-between p-3 bg-white border-2 border-orange-500 rounded-xl cursor-pointer transition gateway-card">
                            <div class="flex items-center gap-2"><span>🔵</span><p class="font-bold text-stone-800">GCash / Digital Wallet</p></div>
                            <input type="radio" name="payment_method" value="GCash" checked class="accent-orange-500">
                        </label>

                        <label class="flex items-center justify-between p-3 bg-stone-50 border border-stone-200 rounded-xl cursor-pointer hover:border-orange-500 transition gateway-card">
                            <div class="flex items-center gap-2"><span>🟢</span><p class="font-bold text-stone-800">Maya Payment Link</p></div>
                            <input type="radio" name="payment_method" value="Maya" class="accent-orange-500">
                        </label>

                        <div class="border border-stone-200 rounded-xl bg-stone-50 overflow-hidden transition gateway-card-container">
                            <label class="flex items-center justify-between p-3 cursor-pointer">
                                <div class="flex items-center gap-2">
                                    <span>💳🔴🟠🎫</span>
                                    <div>
                                        <p class="font-bold text-stone-800">Credit Card / Debit Card</p>
                                        <span class="text-[9px] text-stone-400 font-bold tracking-wide">VISA • MASTERCARD • AMEX</span>
                                    </div>
                                </div>
                                <input type="radio" name="payment_method" value="CreditCard" class="accent-orange-500">
                            </label>

                            <div id="creditCardFields" class="hidden p-4 border-t border-stone-200 bg-white space-y-3">
                                <div class="relative">
                                    <input type="text" id="cc_num" name="cc_number" placeholder="Card number" class="w-full border border-stone-200 rounded-xl px-3 py-2.5 text-stone-900 focus:outline-none focus:border-orange-500 pr-10">
                                    <span class="absolute right-3 top-3 text-stone-300">🔒</span>
                                </div>
                                <div class="grid grid-cols-2 gap-2">
                                    <input type="text" id="cc_exp" name="cc_exp" placeholder="Exp date (MM / YY)" class="w-full border border-stone-200 rounded-xl px-3 py-2.5 text-stone-900 focus:outline-none focus:border-orange-500">
                                    <input type="password" id="cc_cvv" name="cc_cvv" placeholder="Security code" class="w-full border border-stone-200 rounded-xl px-3 py-2.5 text-stone-900 focus:outline-none focus:border-orange-500">
                                </div>
                                <input type="text" id="cc_name" placeholder="Name on card" class="w-full border border-stone-200 rounded-xl px-3 py-2.5 text-stone-900 focus:outline-none focus:border-orange-500">
                            </div>
                        </div>

                        <label class="flex items-center justify-between p-3 bg-stone-50 border border-stone-200 rounded-xl cursor-pointer hover:border-orange-500 transition gateway-card">
                            <div class="flex items-center gap-2"><span>💵</span><p class="font-bold text-stone-800">Cash on Delivery (COD)</p></div>
                            <input type="radio" name="payment_method" value="COD" class="accent-orange-500">
                        </label>
                    </div>
                </div>

                <button type="submit" class="w-full bg-orange-600 text-white text-xs font-black uppercase tracking-wider py-4 rounded-xl shadow-md hover:bg-orange-700 transition">
                    Place Order
                </button>
            </form>
        </div>

        <div class="md:col-span-5 space-y-4">
            <div class="bg-stone-900 text-white p-5 rounded-2xl shadow-sm">
                <h3 class="font-black text-xs uppercase tracking-wider text-stone-400 mb-4">Order Breakdown</h3>
                <div class="divide-y divide-white/10 max-h-[340px] overflow-y-auto space-y-3 mb-4 pr-1">
                    @foreach(session('cart', []) as $item)
                    <div class="flex justify-between items-center text-xs pt-3 first:pt-0">
                        <div class="flex items-center gap-3">
                            <div class="relative shrink-0 bg-white rounded-xl p-1 w-12 h-12 flex items-center justify-center">
                                <img src="{{ $item['image'] ?? '/images/placeholder.jpg' }}" alt="{{ $item['name'] }}" class="w-full h-full object-contain rounded-lg">
                                <span class="absolute -top-1.5 -right-1.5 bg-orange-500 text-white font-extrabold text-[10px] w-5 h-5 rounded-full flex items-center justify-center border-2 border-stone-900 shadow-sm">
                                    {{ $item['quantity'] }}
                                </span>
                            </div>
                            <div class="pr-2">
                                <p class="font-bold text-white leading-tight max-w-[140px] wrap-break-word">{{ $item['name'] }}</p>
                                <p class="text-[10px] text-stone-400 mt-0.5">₱{{ number_format($item['price'], 2) }} each</p>
                            </div>
                        </div>
                        <span class="font-bold text-stone-200 shrink-0">₱{{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                    </div>
                    @endforeach
                </div>
                <div class="border-t border-white/20 pt-4 flex justify-between items-center">
                    <span class="text-xs font-bold text-stone-400">Total Settlement Due:</span>
                    <span class="text-base font-black text-orange-400">₱{{ number_format(array_sum(array_map(function($i){ return $i['price']*$i['quantity']; }, session('cart', []))), 2) }}</span>
                </div>
            </div>
        </div>
    </main>

    <div id="paymentGatewayModal" class="fixed inset-0 bg-black/70 z-50 hidden items-center justify-center p-4">
        <div class="bg-white w-full max-w-xs rounded-2xl p-6 text-center shadow-2xl space-y-4">
            <div id="gatewayLogo" class="text-2xl">📱</div>
            <div>
                <h4 id="gatewayTitle" class="font-black text-sm text-stone-800">Secure Gateway Routing</h4>
                <p class="text-[11px] text-stone-400 mt-1">Please enter your transaction authentication pin code to authorize checkout payment.</p>
            </div>
            <input type="password" id="gatewayPin" maxlength="6" placeholder="••••••" class="w-full text-center font-bold tracking-widest text-lg bg-stone-50 border p-2 rounded-xl focus:outline-orange-500">
            <button onclick="confirmGatewayAuthorization()" class="w-full bg-stone-950 text-white font-bold text-xs uppercase py-3 rounded-xl hover:bg-orange-600 transition">
                Verify & Pay Now
            </button>
        </div>
    </div>

    <script>
        // Toggle card styles on interaction selection change
        document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
            radio.addEventListener('change', function() {
                document.querySelectorAll('.gateway-card').forEach(el => {
                    el.classList.remove('border-orange-500', 'bg-white', 'border-2');
                    el.classList.add('border-stone-200', 'bg-stone-50', 'border');
                });

                const cardContainer = document.querySelector('.gateway-card-container');
                cardContainer.classList.remove('border-orange-500', 'border-2');
                cardContainer.classList.add('border-stone-200');
                document.getElementById('creditCardFields').classList.add('hidden');

                if (this.value === 'CreditCard') {
                    cardContainer.classList.add('border-orange-500', 'border-2');
                    cardContainer.classList.remove('border-stone-200');
                    document.getElementById('creditCardFields').classList.remove('hidden');
                } else {
                    const parentLabel = this.closest('.gateway-card');
                    if(parentLabel) {
                        parentLabel.classList.remove('border-stone-200', 'bg-stone-50', 'border');
                        parentLabel.classList.add('border-orange-500', 'bg-white', 'border-2');
                    }
                }
            });
        });

        // AJAX Form Interceptor to prevent the raw JSON response page dump
        document.getElementById('secureOrderForm').addEventListener('submit', function(e) {
            const method = document.querySelector('input[name="payment_method"]:checked').value;

            // If COD, bypass dynamic AJAX modal and submit like a standard form post
            if (method === 'COD') return true;

            e.preventDefault();

            // Validate inputs if credit card option is checked
            if (method === 'CreditCard') {
                if(!document.getElementById('cc_num').value || !document.getElementById('cc_exp').value || !document.getElementById('cc_cvv').value) {
                    alert('Please complete all card payment credential inputs.');
                    return false;
                }
                document.getElementById('gatewayLogo').innerText = "💳🔴🟠🎫";
                document.getElementById('gatewayTitle').innerText = "Bank Secure 3D-Secure";
            } else if (method === 'Maya') {
                document.getElementById('gatewayLogo').innerText = "🟢";
                document.getElementById('gatewayTitle').innerText = "Maya PaySecure Terminal";
            } else {
                document.getElementById('gatewayLogo').innerText = "🔵";
                document.getElementById('gatewayTitle').innerText = "GCash Authentication Portal";
            }

            document.getElementById('gatewayPin').value = "";
            document.getElementById('paymentGatewayModal').classList.replace('hidden', 'flex');
        });

        function confirmGatewayAuthorization() {
            const pin = document.getElementById('gatewayPin').value;
            if(pin.length < 4) {
                alert('Please input a valid security transaction pin.');
                return;
            }

            document.getElementById('paymentGatewayModal').classList.replace('flex', 'hidden');

            const form = document.getElementById('secureOrderForm');
            const formData = new FormData(form);

            // Execute secure backend API transaction check
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    window.location.href = data.redirect;
                } else {
                    alert('❌ Checkout Issue: ' + (data.message || data.error));
                }
            })
            .catch(err => {
                console.error(err);
                alert('Integration connection failure encountered.');
            });
        }
    </script>
</body>
</html>
