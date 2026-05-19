<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Furecipe Admin Panel 🐾</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-[#FDF7F2] text-stone-900 min-h-screen flex flex-col">

    <header class="bg-stone-950 text-white p-4 sticky top-0 z-50 shadow-md">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center gap-2">
                <span class="text-lg">🛡️</span>
                <h1 class="text-sm font-black uppercase tracking-wider text-orange-400">Furecipe Systems Management Hub</h1>
            </div>
            <a href="/dashboard?tab=home" class="text-xs bg-stone-800 hover:bg-stone-700 text-stone-200 px-4 py-2 rounded-xl transition">
                ← Go to Client App
            </a>
        </div>
    </header>

    <main class="max-w-7xl mx-auto p-4 md:p-8 w-full flex-1 space-y-8">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white border border-stone-200/60 p-6 rounded-2xl shadow-xs">
                <p class="text-[10px] font-bold text-stone-400 uppercase tracking-widest">💰 Gross Revenue Collected</p>
                <h2 class="text-2xl font-black text-emerald-600 mt-1">₱{{ number_format($totalEarnings, 2) }}</h2>
            </div>
            <div class="bg-white border border-stone-200/60 p-6 rounded-2xl shadow-xs">
                <p class="text-[10px] font-bold text-stone-400 uppercase tracking-widest">📦 Processed Invoices</p>
                <h2 class="text-2xl font-black text-stone-800 mt-1">{{ $totalOrders }} Orders</h2>
            </div>
            <div class="bg-white border border-stone-200/60 p-6 rounded-2xl shadow-xs">
                <p class="text-[10px] font-bold text-stone-400 uppercase tracking-widest">🥩 Live Catalog Products</p>
                <h2 class="text-2xl font-black text-orange-600 mt-1">{{ $totalProducts }} Items Available</h2>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

            <div class="lg:col-span-5 bg-white border border-stone-200/60 p-6 rounded-2xl shadow-xs space-y-4">
                <div class="border-b pb-2">
                    <h3 class="font-black text-sm text-stone-800">🛍️ Product Inventory Manager</h3>
                    <p class="text-[11px] text-stone-400">Update naming layouts, adjust currency prices, and descriptions.</p>
                </div>

                <div class="space-y-6 divide-y divide-stone-100">
                    @forelse($products as $product)
                    <form action="{{ route('admin.product.update', $product->id) }}" method="POST" class="pt-4 first:pt-0 space-y-2">
                        @csrf
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-stone-100 rounded-lg shrink-0 flex items-center justify-center font-bold text-stone-400">
                                🖼️
                            </div>
                            <div class="w-full">
                                <label class="block text-[10px] font-bold text-stone-400 uppercase">Product Name</label>
                                <input type="text" name="name" value="{{ $product->name }}" class="w-full font-bold text-xs bg-stone-50 border p-2 rounded-lg focus:outline-orange-500">
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-2">
                            <div class="col-span-1">
                                <label class="block text-[10px] font-bold text-stone-400 uppercase">Price (PHP)</label>
                                <input type="number" name="price" value="{{ $product->price }}" class="w-full font-bold text-xs bg-stone-50 border p-2 rounded-lg focus:outline-orange-500 text-orange-600">
                            </div>
                            <div class="col-span-2">
                                <label class="block text-[10px] font-bold text-stone-400 uppercase">Description</label>
                                <textarea name="description" rows="1" class="w-full text-xs bg-stone-50 border p-2 rounded-lg focus:outline-orange-500 resize-none">{{ $product->description }}</textarea>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="bg-stone-900 hover:bg-stone-800 text-white text-[10px] font-bold uppercase px-3 py-1.5 rounded-md transition shadow-xs">
                                Save Changes
                            </button>
                        </div>
                    </form>
                    @empty
                    <div class="space-y-4 text-xs text-stone-500">
                        <p class="font-bold">✨ Live Sandbox Mock Product Example:</p>
                        <div class="p-3 bg-stone-50 rounded-xl border space-y-2">
                            <p class="font-bold">Allergy-Safe Dog Food Kit</p>
                            <p class="text-orange-600 font-bold">₱5,400.00</p>
                            <p class="text-[11px]">Premium formulation ready to embrace real-food feeding.</p>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>

            <div class="lg:col-span-7 bg-white border border-stone-200/60 p-6 rounded-2xl shadow-xs space-y-4">
                <div class="border-b pb-2">
                    <h3 class="font-black text-sm text-stone-800">📦 Live Incoming Orders & Settlement Audit</h3>
                    <p class="text-[11px] text-stone-400">Review user data metrics, track payouts, and set delivery status flags.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="border-b border-stone-200 text-stone-400 font-bold text-[10px] uppercase tracking-wider">
                                <th class="pb-3">Customer</th>
                                <th class="pb-3">Items / Revenue</th>
                                <th class="pb-3">Pipeline Status</th>
                                <th class="pb-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100">
                            @forelse($orders as $order)
                            <tr>
                                <td class="py-3.5 pr-2">
                                    <p class="font-bold text-stone-800">{{ $order->customer_name }}</p>
                                    <p class="text-[10px] text-stone-400">{{ $order->customer_email }}</p>
                                </td>
                                <td class="py-3.5">
                                    <p class="font-bold text-stone-900">₱{{ number_format($order->total_price, 2) }}</p>
                                    <p class="text-[10px] text-stone-400">Via {{ $order->payment_method }}</p>
                                </td>
                                <td class="py-3.5">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase
                                        {{ $order->status === 'Completed' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td class="py-3.5 text-right">
                                    <form action="{{ route('admin.order.update', $order->id) }}" method="POST" class="inline-flex gap-1 items-center">
                                        @csrf
                                        <select name="status" class="bg-stone-50 border text-[11px] p-1 rounded-md focus:outline-none focus:border-orange-500">
                                            <option value="Pending" {{ $order->status === 'Pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="Completed" {{ $order->status === 'Completed' ? 'selected' : '' }}>Completed</option>
                                            <option value="Cancelled" {{ $order->status === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                        <button type="submit" class="bg-orange-600 text-white text-[10px] font-bold px-2 py-1 rounded-md hover:bg-orange-700 transition">
                                            ✓
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr class="text-stone-500">
                                <td class="py-4">
                                    <p class="font-bold text-stone-800">Cassandra Perez</p>
                                    <p class="text-[10px] text-stone-400">cassandra@example.com</p>
                                </td>
                                <td class="py-4">
                                    <p class="font-bold text-stone-900">₱16,400.00</p>
                                    <p class="text-[10px] text-orange-500">3 Items (GCash)</p>
                                </td>
                                <td class="py-4">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-amber-100 text-amber-800">
                                        Pending Payment Check
                                    </span>
                                </td>
                                <td class="py-4 text-right">
                                    <span class="text-[11px] font-bold text-stone-400 italic">Database Sync Ready</span>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </main>

</body>
</html>
