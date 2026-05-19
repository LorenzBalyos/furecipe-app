<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<style>
    [x-cloak] { display: none !important; }
</style>

<div class="space-y-4 max-w-md mx-auto" x-data="{ openModal: null }">
    <div class="bg-white p-6 rounded-4xl border border-stone-200/60 shadow-xs text-center">
        <div class="w-16 h-16 bg-orange-100 rounded-full mx-auto flex items-center justify-center text-xl mb-2">👤</div>
        <h2 class="text-base font-black text-stone-900">{{ session('user_name', 'Pet Parent') }}</h2>
        <p class="text-xs text-stone-400">{{ session('user_email') }}</p>

        <a href="/logout" class="mt-4 inline-block bg-red-50 text-red-600 text-xs font-black px-4 py-2 rounded-xl transition hover:bg-red-100">
            Log Out of Account
        </a>
    </div>

    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-3.5 rounded-2xl text-xs font-bold text-center animate-bounce">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white p-5 rounded-3xl border border-stone-200/60 shadow-xs">
        <h3 class="text-sm font-black text-stone-900 mb-3 flex items-center gap-1.5">📦 <span>Order Tracking Logs</span></h3>
        <div class="space-y-3 max-h-[280px] overflow-y-auto pr-1">
            @forelse($orders as $order)
            <div class="bg-stone-50 p-3 rounded-2xl border border-stone-100 text-xs space-y-1.5">
                <div class="flex justify-between items-center font-bold">
                    <span class="text-stone-400 font-mono text-[10px]">#{{ strtoupper(substr($order['id'] ?? $loop->index, 0, 8)) }}</span>
                    <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold tracking-wider uppercase {{ ($order['order_status'] ?? 'Processing') === 'Paid Successfully' || ($order['order_status'] ?? '') === 'PAYMENT VERIFIED SUCCESSFULLY' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                        {{ $order['order_status'] ?? 'Processing' }}
                    </span>
                </div>

                <div class="border-t border-b border-stone-200/40 py-1.5 space-y-1">
                    @if(isset($order['items']) && is_array($order['items']))
                        @foreach($order['items'] as $item)
                        <div class="flex justify-between text-stone-600 text-[11px]">
                            <p class="truncate max-w-[180px]">{{ $item['name'] ?? 'Product SKU' }}</p>
                            <span class="font-semibold text-stone-400">x{{ $item['quantity'] ?? 1 }}</span>
                        </div>
                        @endforeach
                    @endif
                </div>

                <div class="flex justify-between items-center pt-0.5">
                    <span class="text-[9px] font-bold text-stone-400 uppercase tracking-tight">Via {{ $order['payment_mode'] ?? 'COD' }}</span>
                    <p class="font-black text-orange-600">₱{{ number_format($order['grand_total'] ?? 0, 2) }}</p>
                </div>
            </div>
            @empty
            <div class="text-center py-6 text-stone-400">
                <p class="text-lg">🛒</p>
                <p class="text-[11px] font-medium mt-1">No historic purchase logs found in Firestore.</p>
            </div>
            @endforelse
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-stone-200/60 shadow-xs divide-y divide-stone-100 overflow-hidden text-xs">
        <div class="p-4 bg-stone-50/75">
            <h4 class="font-black text-stone-800 uppercase tracking-wider text-[10px]">Application Settings</h4>
        </div>

        <button type="button" @click="openModal = 'profile'" class="w-full text-left flex items-center justify-between p-4 hover:bg-stone-50 transition group focus:outline-none">
            <div class="flex items-center gap-3">
                <span class="text-sm text-stone-500 bg-stone-100 w-8 h-8 rounded-xl flex items-center justify-center group-hover:bg-orange-50 group-hover:text-orange-600 transition">⚙️</span>
                <div>
                    <p class="font-bold text-stone-800">Personal Information</p>
                    <p class="text-[10px] text-stone-400">Manage delivery addresses and contact configs</p>
                </div>
            </div>
            <span class="text-stone-300 group-hover:text-stone-500 font-bold transition">→</span>
        </button>

        <button type="button" @click="openModal = 'contact'" class="w-full text-left flex items-center justify-between p-4 hover:bg-stone-50 transition group focus:outline-none">
            <div class="flex items-center gap-3">
                <span class="text-sm text-stone-500 bg-stone-100 w-8 h-8 rounded-xl flex items-center justify-center group-hover:bg-orange-50 group-hover:text-orange-600 transition">📞</span>
                <div>
                    <p class="font-bold text-stone-800">Contact Us & Support</p>
                    <p class="text-[10px] text-stone-400">Get 24/7 technical assistance for your pets logs</p>
                </div>
            </div>
            <span class="text-stone-300 group-hover:text-stone-500 font-bold transition">→</span>
        </button>

        <button type="button" @click="openModal = 'about'" class="w-full text-left flex items-center justify-between p-4 hover:bg-stone-50 transition group focus:outline-none">
            <div class="flex items-center gap-3">
                <span class="text-sm text-stone-500 bg-stone-100 w-8 h-8 rounded-xl flex items-center justify-center group-hover:bg-orange-50 group-hover:text-orange-600 transition">🐾</span>
                <div>
                    <p class="font-bold text-stone-800">About Furecipe App</p>
                    <p class="text-[10px] text-stone-400">Learn about our nutrition formulas & history</p>
                </div>
            </div>
            <span class="text-stone-300 group-hover:text-stone-500 font-bold transition">→</span>
        </button>

        <button type="button" @click="openModal = 'legal'" class="w-full text-left flex items-center justify-between p-4 hover:bg-stone-50 transition group focus:outline-none">
            <div class="flex items-center gap-3">
                <span class="text-sm text-stone-500 bg-stone-100 w-8 h-8 rounded-xl flex items-center justify-center group-hover:bg-orange-50 group-hover:text-orange-600 transition">⚖️</span>
                <div>
                    <p class="font-bold text-stone-800">Legal & Terms of Service</p>
                    <p class="text-[10px] text-stone-400">Data usage regulations, privacy agreements, refund policies</p>
                </div>
            </div>
            <span class="text-stone-300 group-hover:text-stone-500 font-bold transition">→</span>
        </button>
    </div>

    <div x-show="openModal === 'profile'" x-cloak class="fixed inset-0 bg-stone-900/60 backdrop-blur-xs flex items-center justify-center z-50 p-4" @click.self="openModal = null">
        <div class="bg-white rounded-3xl max-w-sm w-full p-5 space-y-4 shadow-xl border border-stone-100">
            <div class="flex justify-between items-center">
                <h3 class="font-black text-stone-900 text-sm">Update Details</h3>
                <button type="button" @click="openModal = null" class="text-stone-400 hover:text-stone-600 font-bold text-lg">&times;</button>
            </div>
            <form action="/profile/update" method="POST" class="space-y-3 text-xs">
                @csrf
                <div>
                    <label class="block font-bold text-stone-700 mb-1">Full Name</label>
                    <input type="text" name="name" value="{{ session('user_name') }}" class="w-full px-3 py-2 bg-stone-50 border border-stone-200 rounded-xl focus:outline-none focus:border-orange-500 text-stone-800 font-medium">
                </div>
                <div>
                    <label class="block font-bold text-stone-700 mb-1">Email Address</label>
                    <input type="email" name="email" value="{{ session('user_email') }}" class="w-full px-3 py-2 bg-stone-50 border border-stone-200 rounded-xl focus:outline-none focus:border-orange-500 text-stone-800 font-medium">
                </div>
                <div>
                    <label class="block font-bold text-stone-700 mb-1">Contact Number</label>
                    <input type="tel" name="phone" placeholder="+63 912 345 6789" class="w-full px-3 py-2 bg-stone-50 border border-stone-200 rounded-xl focus:outline-none focus:border-orange-500 text-stone-800 font-medium">
                </div>
                <div class="pt-2 flex gap-2">
                    <button type="button" @click="openModal = null" class="flex-1 bg-stone-100 text-stone-600 font-bold py-2 rounded-xl">Cancel</button>
                    <button type="submit" class="flex-1 bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 rounded-xl transition">Save</button>
                </div>
            </form>
        </div>
    </div>

    <div x-show="openModal === 'contact'" x-cloak class="fixed inset-0 bg-stone-900/60 backdrop-blur-xs flex items-center justify-center z-50 p-4" @click.self="openModal = null">
        <div class="bg-white rounded-3xl max-w-sm w-full p-5 space-y-3.5 shadow-xl border border-stone-100 text-xs text-center">
            <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-full flex items-center justify-center text-lg mx-auto">📞</div>
            <h3 class="font-black text-stone-900 text-sm">Furecipe Support</h3>
            <p class="text-stone-500 leading-relaxed px-2">Need help with your companion metrics formulation or have purchase inquiries?</p>
            <div class="bg-stone-50 rounded-2xl p-3 text-left space-y-2 font-medium text-stone-700">
                <p class="flex items-center gap-2">✉️ <span class="font-bold text-stone-900">support@furecipe.app</span></p>
                <p class="flex items-center gap-2">🌐 <span class="font-bold text-stone-900">www.furecipe.app/help</span></p>
            </div>
            <button type="button" @click="openModal = null" class="w-full bg-stone-900 text-white font-bold py-2.5 rounded-xl transition mt-2">Close</button>
        </div>
    </div>

    <div x-show="openModal === 'about'" x-cloak class="fixed inset-0 bg-stone-900/60 backdrop-blur-xs flex items-center justify-center z-50 p-4" @click.self="openModal = null">
        <div class="bg-white rounded-3xl max-w-sm w-full p-5 space-y-3 shadow-xl border border-stone-100 text-xs">
            <div class="flex justify-between items-center">
                <h3 class="font-black text-stone-900 text-sm">About Furecipe</h3>
                <button type="button" @click="openModal = null" class="text-stone-400 hover:text-stone-600 font-bold text-lg">&times;</button>
            </div>
            <div class="space-y-2.5 text-stone-600 leading-relaxed overflow-y-auto max-h-[300px] pr-1">
                <p class="font-bold text-orange-600 text-xs">Your Pet's Custom Wellness Companion 🐾</p>
                <p>Furecipe helps modern pet owners eliminate formulation guessing. We track essential nutritional values, record meal profiles, and process transactional records securely inside integrated cloud spaces.</p>
                <p>Version: <span class="font-mono bg-stone-100 px-1.5 py-0.5 rounded-sm font-bold text-stone-800">1.0.0-apk-rc2</span></p>
            </div>
            <button type="button" @click="openModal = null" class="w-full bg-stone-100 text-stone-700 font-bold py-2 rounded-xl mt-2 transition hover:bg-stone-200">Got it</button>
        </div>
    </div>

    <div x-show="openModal === 'legal'" x-cloak class="fixed inset-0 bg-stone-900/60 backdrop-blur-xs flex items-center justify-center z-50 p-4" @click.self="openModal = null">
        <div class="bg-white rounded-3xl max-w-sm w-full p-5 space-y-3 shadow-xl border border-stone-100 text-xs">
            <div class="flex justify-between items-center">
                <h3 class="font-black text-stone-900 text-sm">Terms of Service</h3>
                <button type="button" @click="openModal = null" class="text-stone-400 hover:text-stone-600 font-bold text-lg">&times;</button>
            </div>
            <div class="space-y-2 text-stone-500 leading-relaxed text-[11px] overflow-y-auto max-h-[260px] pr-1">
                <p class="font-bold text-stone-800">1. Data Storage Regulations</p>
                <p>By using the Furecipe App application architecture, you acknowledge that your profiles, metrics configurations, and baseline transactions are recorded securely inside cloud database structures.</p>
                <p class="font-bold text-stone-800">2. Refund & Verification Policies</p>
                <p>All items verified successfully undergo multi-layer logistics distributions. Standard physical orders tracked locally operate under clear structural compliance guidelines.</p>
            </div>
            <button type="button" @click="openModal = null" class="w-full bg-stone-900 text-white font-bold py-2 rounded-xl mt-2">Accept Terms</button>
        </div>
    </div>
</div>
