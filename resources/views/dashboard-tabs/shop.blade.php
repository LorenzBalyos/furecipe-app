<div>
    <h2 class="text-xl font-black text-stone-900 mb-4">Holistic Vet Blend Store</h2>
    <div class="grid grid-cols-2 gap-3">
        @forelse($products as $product)
            @php
    // Clean references for match validation check
    $prodId = $product['id'] ?? '';
    $prodName = $product['name'] ?? '';

    // Fallback default details
    $finalDescription = $product['description'] ?? 'Premium supplement premix formulation.';
    $finalNutrients = $product['target_nutrients'] ?? 'Essential Minerals';
    $finalLifeStage = $product['life_stage'] ?? 'All Life Stages';
    $finalServing = $product['serving_size'] ?? 'See Recipe Guide';

    // =========================================================
    //      CUSTOM DESCRIPTION OVERRIDES FOR INDIVIDUAL PRODUCTS
    // =========================================================
    if (stripos($prodName, 'Icelandic') !== false || stripos($prodName, 'Fish Oil') !== false) {
        $finalDescription = 'Pure, sustainably sourced marine oil from the clean waters of Iceland. Provides an ultra-potent concentration of long-chain fatty acids to eliminate skin flaking, reduce joint stiffness, and enhance coat gloss.';
        $finalNutrients = 'EPA (Eicosapentaenoic Acid), DHA (Docosahexaenoic Acid), Omega-3';
        $finalLifeStage = 'Dogs & Cats of All Sizes';
        $finalServing = '1/4 Teaspoon per 20 lbs of body weight daily';
    }
    elseif (stripos($prodName, 'Starter Set') !== false || stripos($prodName, 'Starter Kit') !== false) {
        $finalDescription = 'The ultimate introductory pack containing essential recipe guide baselines and premium mineral balancing samples. Perfect for pet parents transitioning away from commercial kibble into fresh food.';
        $finalNutrients = 'Comprehensive Vitamin Mix, Calcium, Instruction Booklet';
        $finalLifeStage = 'Beginner Pet Parents / All Life Stages';
        $finalServing = 'Follow included step-by-step recipe card configurations';
    }
    elseif (stripos($prodName, 'Green Omega') !== false) {
        $finalDescription = 'Eco-conscious, plant-based and marine algae alternative rich in protective phytocompounds. Formulated to optimize structural heart metrics and neurological function while remaining fully hypoallergenic.';
        $finalNutrients = 'Algal DHA, Antioxidants, Plant-Derived Fatty Acids';
        $finalLifeStage = 'Adult Dogs & Sensitive Systems';
        $finalServing = 'Mix 1 level scoop into morning baseline food routine';
    }
    elseif (stripos($prodName, 'Canine') !== false || stripos($prodName, 'Dog') !== false) {
        $finalDescription = 'Expertly formulated blend optimized to balance home-prepared meals for dogs. Delivers vital trace elements and macro-minerals absent from plain muscle meats.';
        $finalNutrients = 'Calcium, Zinc, Iron, Vitamin D3, Vitamin E';
        $finalLifeStage = 'Adult Dogs & Seniors';
        $finalServing = '1 Regular scoop per 500g Fresh Meat';
    }
    elseif (stripos($prodName, 'Feline') !== false || stripos($prodName, 'Cat') !== false) {
        $finalDescription = 'Premium nutritional modifier enriched with essential Taurine to fulfill fundamental obligatory feline health parameters. Keeps indoor systems active, preserving metabolic functions perfectly.';
        $finalNutrients = 'Taurine, Calcium Carbonate, Copper, B-Complex';
        $finalLifeStage = 'Adult Cats & Growing Kittens';
        $finalServing = '1/2 Small scoop blended into daily wet food';
    }
@endphp

            <div class="bg-white p-3 rounded-2xl border border-stone-200/60 flex flex-col justify-between shadow-xs hover:border-orange-200 transition cursor-pointer"
                 onclick="openProductModal(
                    '{{ $prodId }}',
                    '{{ addslashes($prodName) }}',
                    '{{ $product['price'] ?? 0 }}',
                    '{{ $product['image'] ?? '' }}',
                    '{{ addslashes($finalDescription) }}',
                    '{{ addslashes($finalNutrients) }}',
                    '{{ addslashes($finalLifeStage) }}',
                    '{{ addslashes($finalServing) }}'
                 )">
                <div>
                    <div class="w-full aspect-square bg-white rounded-xl mb-2 overflow-hidden flex items-center justify-center p-2">
                        <img src="{{ $product['image'] ?? '' }}" onerror="this.src='https://placehold.co/150x150/f4f4f5/18181b?text=Premix';" class="w-full h-full object-contain scale-105">
                    </div>
                    <h3 class="text-xs font-black text-stone-900 leading-tight mb-1 max-w-full truncate">
                        {{ $prodName ?: 'Supplement Item' }}
                    </h3>
                </div>

                <div class="flex justify-between items-center pt-2 mt-2 border-t border-stone-100" onclick="event.stopPropagation();">
                    <span class="text-xs font-black text-orange-600">₱{{ number_format($product['price'] ?? 0, 0) }}</span>
                    <button type="button"
                            onclick="openProductModal(
                                '{{ $prodId }}',
                                '{{ addslashes($prodName) }}',
                                '{{ $product['price'] ?? 0 }}',
                                '{{ $product['image'] ?? '' }}',
                                '{{ addslashes($finalDescription) }}',
                                '{{ addslashes($finalNutrients) }}',
                                '{{ addslashes($finalLifeStage) }}',
                                '{{ addslashes($finalServing) }}'
                            )" class="bg-stone-900 text-white text-[10px] px-2.5 py-1 rounded-lg font-black hover:bg-orange-600 transition">
                        Add
                    </button>
                </div>
            </div>
        @empty
            <p class="text-xs text-stone-400 col-span-2 text-center py-6">No shop products populated.</p>
        @endforelse
    </div>
</div>
