<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Workspace | Furecipe</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#FDF7F2] text-stone-900 pb-28">

    <nav class="bg-white p-4 border-b border-stone-100 flex justify-between items-center sticky top-0 z-40 shadow-xs max-w-md mx-auto rounded-b-2xl">
        <div>
            <span class="text-xs font-black tracking-tighter text-orange-600 cursor-pointer" onclick="switchTab('home')">FURECIPE🐾</span>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="switchTab('home')" class="bg-stone-100 hover:bg-stone-200 text-stone-800 p-2 rounded-xl text-xs transition font-bold flex items-center justify-center">
                HOME
            </button>
            <button onclick="toggleCart()" class="bg-stone-900 text-white px-3 py-1.5 rounded-xl font-bold text-xs flex items-center gap-1">
                🛒 <span id="cartCountBadge">{{ count(session('cart', [])) }}</span>
            </button>
        </div>
    </nav>

    <main class="max-w-md mx-auto p-4">
        <div id="view-home" class="tab-content hidden">
            @include('dashboard-tabs.home')
        </div>

        <div id="view-shop" class="tab-content hidden">
            @include('dashboard-tabs.shop')
        </div>

        <div id="view-calculator" class="tab-content hidden">
            @include('dashboard-tabs.calculator')
        </div>

        <div id="view-plan" class="tab-content hidden">
            @include('dashboard-tabs.plan')
        </div>

        <div id="view-blog" class="tab-content hidden">
            @include('dashboard-tabs.blog')
        </div>

        <div id="view-me" class="tab-content hidden">
            @include('dashboard-tabs.me')
        </div>
    </main>

    <div id="productModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4">
        <div class="bg-white w-full max-w-sm rounded-3xl overflow-hidden shadow-2xl flex flex-col max-h-[90vh]">
            <div class="relative bg-stone-50 p-6 flex justify-center items-center border-b border-stone-100">
                <button onclick="closeProductModal()" class="absolute top-4 right-4 bg-stone-900/10 text-stone-800 w-7 h-7 rounded-full flex items-center justify-center font-black text-xs">✕</button>
                <img id="modalProductImage" src="" class="max-h-48 object-contain">
            </div>
            <div class="p-5 flex-1 overflow-y-auto space-y-4">
                <div>
                    <h2 id="modalProductName" class="text-base font-black text-stone-900 leading-tight"></h2>
                    <p id="modalProductPrice" class="text-sm font-black text-orange-600 mt-1"></p>
                </div>

                <div class="space-y-1">
                    <span class="text-[10px] font-black uppercase tracking-wider text-stone-400">Description</span>
                    <p id="modalProductDescription" class="text-xs text-stone-500 leading-relaxed"></p>
                </div>

                <div class="bg-stone-50 p-3 rounded-xl border border-stone-100 space-y-2 text-[11px]">
                    <div class="flex justify-between items-start gap-4">
                        <span class="text-stone-400 font-bold whitespace-nowrap">Target Nutrients:</span>
                        <span id="modalProductNutrients" class="font-black text-stone-800 text-right"></span>
                    </div>
                    <div class="flex justify-between items-center border-t border-stone-200/60 pt-2">
                        <span class="text-stone-400 font-bold">Life Stage:</span>
                        <span id="modalProductLifeStage" class="font-black text-stone-800 text-right"></span>
                    </div>
                    <div class="flex justify-between items-center border-t border-stone-200/60 pt-2">
                        <span class="text-stone-400 font-bold">Serving Size:</span>
                        <span id="modalProductServing" class="font-black text-stone-800 text-right"></span>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-1">
                    <span class="text-xs font-bold text-stone-400 uppercase">Quantity</span>
                    <div class="flex items-center gap-1 border border-stone-200 rounded-xl p-1 bg-stone-50">
                        <button onclick="adjustModalQty(-1)" class="w-7 h-7 font-bold text-stone-600 text-sm bg-white border border-stone-100 shadow-2xs rounded-lg">-</button>
                        <span id="modalQtyValue" class="w-8 text-center text-xs font-black">1</span>
                        <button onclick="adjustModalQty(1)" class="w-7 h-7 font-bold text-stone-600 text-sm bg-white border border-stone-100 shadow-2xs rounded-lg">+</button>
                    </div>
                </div>
            </div>
            <div class="p-4 border-t border-stone-100 bg-stone-50">
                <button id="modalActionBtn" class="w-full bg-orange-600 text-white text-xs font-black uppercase tracking-wider py-3.5 rounded-xl shadow-sm hover:bg-orange-700 transition">
                    Add To Basket
                </button>
            </div>
        </div>
    </div>

    <div id="cartOverlay" class="fixed inset-0 bg-black/40 z-50 hidden justify-end items-center">
        <div class="h-full w-full max-w-md bg-stone-900 text-white p-4 flex flex-col justify-between shadow-2xl ml-auto">
            <div id="cartMainScreen" class="flex flex-col h-full justify-between">
                <div>
                    <div class="flex justify-between items-center border-b border-white/10 pb-3 mb-4">
                        <h3 class="font-black text-sm">🛒 Current Basket</h3>
                        <button onclick="toggleCart()" class="text-stone-400 text-xs">Close ✕</button>
                    </div>
                    @php $cart = session('cart', []); $grandTotal = 0; @endphp
                    @if(!empty($cart))
                        <div class="space-y-3 overflow-y-auto max-h-[68vh] pr-1">
                            @foreach($cart as $id => $item)
                                @php $grandTotal += ($item['price'] * $item['quantity']); @endphp
                                <div class="bg-white/5 p-2 rounded-xl flex justify-between items-center text-xs gap-2">
                                    <div class="w-10 h-10 bg-white rounded-lg p-0.5 shrink-0 flex items-center justify-center overflow-hidden">
                                        <img src="{{ $item['image'] ?? 'https://placehold.co/150' }}" class="max-h-full object-contain">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-bold text-white truncate text-[11px]">{{ $item['name'] }}</h4>
                                        <p class="text-[10px] text-stone-400">₱{{ number_format($item['price'], 0) }}</p>
                                    </div>
                                    <div class="flex items-center gap-1 bg-black/40 rounded-lg p-0.5">
                                        <button onclick="updateCartQty('{{ $id }}', -1)" class="w-5 h-5 bg-white/10 rounded font-bold flex items-center justify-center text-[10px] text-stone-300">-</button>
                                        <span class="w-4 text-center font-bold text-[10px]">{{ $item['quantity'] }}</span>
                                        <button onclick="updateCartQty('{{ $id }}', 1)" class="w-5 h-5 bg-white/10 rounded font-bold flex items-center justify-center text-[10px] text-stone-300">+</button>
                                    </div>
                                    <button onclick="removeCartItem('{{ $id }}')" class="text-stone-500 hover:text-red-400 px-1 font-bold">✕</button>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p id="emptyCartMessage" class="text-center text-xs text-stone-500 py-12">Your cart is completely empty.</p>
                    @endif
                </div>

                @if(!empty($cart))
                <div class="border-t border-white/10 pt-3 mt-4">
                    <div class="flex justify-between text-xs mb-3">
                        <span class="text-stone-400">Total:</span>
                        <span class="font-black text-white text-sm">₱{{ number_format($grandTotal, 2) }}</span>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <form action="/cart/clear" method="POST" class="col-span-1">
                            @csrf
                            <button type="submit" class="w-full bg-stone-800 text-stone-400 py-2.5 rounded-xl text-[10px] font-bold uppercase">Clear</button>
                        </form>
                        <button onclick="proceedToCheckout()" class="col-span-2 bg-orange-600 text-white py-2.5 rounded-xl text-[11px] font-black uppercase text-center tracking-wider shadow-md hover:bg-orange-700 transition">Checkout →</button>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-stone-200 px-2 py-2 flex justify-around items-center z-40 shadow-2xl max-w-md mx-auto rounded-t-3xl">
        <button onclick="switchTab('shop')" id="nav-shop" class="flex flex-col items-center gap-0.5 text-stone-400 nav-btn-item">
            <span class="text-lg">🛒</span><span class="text-[8px] font-black uppercase tracking-wider">Shop</span>
        </button>
        <button onclick="switchTab('calculator')" id="nav-calculator" class="flex flex-col items-center gap-0.5 text-stone-400 nav-btn-item">
            <span class="text-lg">🧮</span><span class="text-[8px] font-black uppercase tracking-wider">Calculator</span>
        </button>
        <button onclick="switchTab('plan')" id="nav-plan" class="flex flex-col items-center gap-0.5 text-stone-400 nav-btn-item">
            <span class="text-lg">📋</span><span class="text-[8px] font-black uppercase tracking-wider">Plan</span>
        </button>
        <button onclick="switchTab('blog')" id="nav-blog" class="flex flex-col items-center gap-0.5 text-stone-400 nav-btn-item">
            <span class="text-lg">📰</span><span class="text-[8px] font-black uppercase tracking-wider">Blog</span>
        </button>
        <button onclick="switchTab('me')" id="nav-me" class="flex flex-col items-center gap-0.5 text-stone-400 nav-btn-item">
            <span class="text-lg">👤</span><span class="text-[8px] font-black uppercase tracking-wider">Me</span>
        </button>
    </div>

    <script>
    let activeModalProduct = null;
    let activeTabState = '{{ $activeTab ?? "home" }}';

    function switchTab(tabId) {
        activeTabState = tabId;
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('.nav-btn-item').forEach(btn => btn.classList.replace('text-orange-600', 'text-stone-400'));

        const targetContent = document.getElementById('view-' + tabId);
        if(targetContent) targetContent.classList.remove('hidden');

        const targetNav = document.getElementById('nav-' + tabId);
        if(targetNav) targetNav.classList.replace('text-stone-400', 'text-orange-600');
    }

    function toggleCart() {
        const overlay = document.getElementById('cartOverlay');
        if (overlay.classList.contains('hidden')) {
            overlay.classList.replace('hidden', 'flex');
        } else {
            overlay.classList.replace('flex', 'hidden');
        }
    }

    function openProductModal(id, name, price, image, desc, nutrients, lifeStage, serving) {
        activeModalProduct = { id, name, price, image };
        document.getElementById('modalProductName').innerText = name;
        document.getElementById('modalProductPrice').innerText = '₱' + parseFloat(price).toLocaleString();
        document.getElementById('modalProductDescription').innerText = desc || 'Premium supplement premix formulation.';

        document.getElementById('modalProductNutrients').innerText = nutrients || 'Essential Minerals';
        document.getElementById('modalProductLifeStage').innerText = lifeStage || 'All Life Stages';
        document.getElementById('modalProductServing').innerText = serving || 'See Recipe Guide';

        document.getElementById('modalProductImage').src = image || 'https://placehold.co/150x150/f4f4f5/18181b?text=Premix';
        document.getElementById('modalQtyValue').innerText = '1';
        document.getElementById('modalActionBtn').setAttribute('onclick', `addModalProductToCart()`);
        document.getElementById('productModal').classList.replace('hidden', 'flex');
    }

    function closeProductModal() {
        document.getElementById('productModal').classList.replace('flex', 'hidden');
        activeModalProduct = null;
    }

    function adjustModalQty(val) {
        let current = parseInt(document.getElementById('modalQtyValue').innerText);
        current += val;
        if(current < 1) current = 1;
        document.getElementById('modalQtyValue').innerText = current;
    }

    function addModalProductToCart() {
        if(!activeModalProduct) return;
        let qty = parseInt(document.getElementById('modalQtyValue').innerText);
        executeCartPost('/cart/add', {
            product_id: activeModalProduct.id,
            name: activeModalProduct.name,
            price: activeModalProduct.price,
            quantity: qty,
            image: activeModalProduct.image
        }, true);
        closeProductModal();
    }

    function updateCartQty(id, delta) {
        executeCartPost('/cart/update', { product_id: id, delta: delta }, true);
    }

    function removeCartItem(id) {
        executeCartPost('/cart/remove', { product_id: id }, true);
    }

    function executeCartPost(url, payload, retainCartOpen = false) {
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(data => {
            let nextUrl = window.location.pathname + '?tab=' + activeTabState;
            if (retainCartOpen) { nextUrl += '&open_cart=1'; }
            window.location.href = nextUrl;
        });
    }

    function proceedToCheckout() {
        window.location.href = '/checkout';
    }

    document.addEventListener("DOMContentLoaded", function() {
        const urlParams = new URLSearchParams(window.location.search);
        const initialTab = urlParams.get('tab') || '{{ $activeTab ?? "home" }}';
        switchTab(initialTab);

        if (urlParams.get('open_cart') === '1') {
            toggleCart();
        }
    });
    </script>
</body>
</html>
