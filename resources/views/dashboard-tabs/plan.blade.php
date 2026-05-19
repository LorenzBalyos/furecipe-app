<div class="space-y-5">
    @if(count($pets) > 0)
        <div class="bg-white rounded-3xl p-5 border border-stone-100 shadow-xs space-y-4 relative overflow-hidden">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-orange-50/40 rounded-full blur-2xl pointer-events-none"></div>

            <div class="flex items-center justify-between gap-4 relative z-10">
                <div class="flex-1 min-w-0">
                    <div class="relative inline-block w-full max-w-60">
                        <select id="weeklyPetSelector" class="w-full bg-transparent font-black text-base text-stone-900 focus:outline-none pr-8 cursor-pointer tracking-tight truncate appearance-none" onchange="updateWeeklyMealPlan()">
                            @foreach($pets as $petItem)
                                @php
                                    $petArray = is_object($petItem) ? (array)$petItem : $petItem;

                                    // 🛠️ Grab the absolute species field from your Firebase document structure
                                    $rawSpecies = strtolower($petArray['species'] ?? $petArray['pet_type'] ?? $petArray['type'] ?? 'dog');

                                    // Robust fallback normalization for any feline variation stored in database
                                    if (str_contains($rawSpecies, 'cat') || str_contains($rawSpecies, 'feline')) {
                                        $inferredType = 'cat';
                                    } else {
                                        $inferredType = 'dog';
                                    }

                                    // Dynamic key matching to grab the raw age value safely
                                    $petAgeStr = '1 Month';
                                    foreach ($petArray as $key => $val) {
                                        $cleanKey = strtolower(str_replace(['"', "'", '_', ' '], '', $key));
                                        if (str_contains($cleanKey, 'age')) {
                                            $petAgeStr = trim($val);
                                            break;
                                        }
                                    }
                                @endphp
                                <option value="{{ $petArray['id'] ?? '' }}"
                                        data-name="{{ trim($petArray['pet_name'] ?? $petArray['name'] ?? 'Your Pet') }}"
                                        data-type="{{ $inferredType }}"
                                        data-breed="{{ trim($petArray['pet_breed'] ?? $petArray['breed'] ?? 'Mixed Breed') }}"
                                        data-age="{{ $petAgeStr }}"
                                        data-weight="{{ (float)($petArray['pet_weight'] ?? $petArray['weight'] ?? 0) }}"
                                        data-weight-unit="{{ trim($petArray['weight_unit'] ?? 'lbs') }}"
                                        data-calories="{{ (float)($petArray['daily_calories'] ?? $petArray['required_calories'] ?? 0) }}"
                                        data-blend-preference="{{ strtolower(trim($petArray['blend_preference'] ?? $petArray['recipe_type'] ?? 'regular')) }}">
                                    🐾 {{ $petArray['pet_name'] ?? $petArray['name'] ?? 'Unnamed Profile' }}
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-2 flex items-center text-stone-500">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M9.293 12.95a1 1 0 001.414 0l4.5-4.5a1 1 0 00-1.414-1.414L10 11.586 5.646 7.232a1 1 0 00-1.414 1.414l4.5 4.5z"/>
                            </svg>
                        </div>
                    </div>

                    <p id="profileMetaBadgeRow" class="text-[10px] text-stone-400 font-bold mt-1 tracking-wider uppercase">
                        <span id="metaBadgeType">--</span> • <span id="metaBadgeBreed">--</span>
                    </p>
                </div>

                <form id="deletePlanForm" action="" method="POST" onsubmit="return confirm('Are you sure you want to completely drop this pet calculation baseline profile?');" class="shrink-0">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-9 h-9 rounded-xl bg-red-50 hover:bg-red-100 text-red-500 flex items-center justify-center transition border border-red-100/40 shadow-2xs">
                        🗑️
                    </button>
                </form>
            </div>

            <div class="grid grid-cols-3 gap-2 bg-stone-50 p-3 rounded-2xl border border-stone-100/80">
                <div class="text-center border-r border-stone-200/60 last:border-0">
                    <span class="block text-[8px] font-black uppercase tracking-wider text-stone-400">Age</span>
                    <span id="cardMetricAge" class="text-xs font-black text-stone-800 block mt-0.5">--</span>
                </div>
                <div class="text-center border-r border-stone-200/60 last:border-0">
                    <span class="block text-[8px] font-black uppercase tracking-wider text-stone-400">Scale Mass</span>
                    <span id="cardMetricWeight" class="text-xs font-black text-stone-800 block mt-0.5">--</span>
                </div>
                <div class="text-center last:border-0">
                    <span class="block text-[8px] font-black uppercase tracking-wider text-stone-400">Target Cal / Day</span>
                    <span id="cardMetricCalories" class="text-xs font-black text-orange-600 block mt-0.5">--</span>
                </div>
            </div>
        </div>

        <div class="flex gap-2 overflow-x-auto pb-1 scrollbar-none snap-x">
            @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                <button type="button"
                        onclick="selectRotationDay(this, '{{ $day }}')"
                        class="weekday-pill shrink-0 snap-center px-4 py-2 text-xs font-bold rounded-xl transition bg-white border border-stone-100 text-stone-600 shadow-2xs {{ $day === 'Monday' ? 'bg-stone-900 text-white! border-stone-900! font-black shadow-xs' : '' }}">
                    {{ $day }}
                </button>
            @endforeach
        </div>

        <div class="bg-white rounded-3xl p-5 border border-stone-100 shadow-xs space-y-4">
            <div class="flex justify-between items-start gap-2 border-b border-stone-100 pb-3">
                <div>
                    <h4 id="displayRecipeName" class="text-sm font-black text-stone-900 leading-tight">--</h4>
                    <p class="text-[10px] text-stone-400 font-semibold mt-0.5">
                        Custom Cooked Recipe Scaling Formulated from Dr. Dody's Guidelines
                    </p>
                </div>
                <div class="text-right whitespace-nowrap">
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[8px] font-black bg-orange-50 text-orange-600 uppercase tracking-wider">Batch Setup</span>
                    <p id="displayRecipeDensity" class="text-[9px] text-stone-400 font-bold mt-1">--</p>
                </div>
            </div>

            <div class="space-y-3">
                <h5 class="text-[9px] font-black text-stone-400 uppercase tracking-wider">1. Full Cookbook Batch Formulation (Calculated Weekly Total)</h5>
                <div class="bg-stone-50/60 rounded-2xl border border-stone-100 p-3 flex items-stretch justify-between text-left">
                    <div class="flex-1 pr-2 flex flex-col justify-start">
                        <span class="text-[9px] text-stone-400 font-black uppercase tracking-wider block border-b border-stone-200/60 pb-1 mb-1.5">🥩 Protein</span>
                        <span id="displayProteinQty" class="font-black text-stone-900 leading-snug text-[11px] block">--</span>
                    </div>
                    <div class="w-px bg-stone-200/80 mx-1 self-stretch"></div>
                    <div class="flex-1 px-2 flex flex-col justify-start">
                        <span class="text-[9px] text-stone-400 font-black uppercase tracking-wider block border-b border-stone-200/60 pb-1 mb-1.5">🥦 Fiber</span>
                        <span id="displayCarbGreenQty" class="font-black text-stone-900 leading-snug text-[11px] block">--</span>
                    </div>
                    <div class="w-px bg-stone-200/80 mx-1 self-stretch"></div>
                    <div class="flex-1 px-2 flex flex-col justify-start bg-orange-50/20 rounded-lg">
                        <span class="text-[9px] text-orange-500 font-black uppercase tracking-wider block border-b border-orange-100 pb-1 mb-1.5">🥄 Vet Blend</span>
                        <span id="displayPremixQty" class="font-black text-orange-600 leading-snug text-[11px] block">--</span>
                    </div>
                    <div class="w-px bg-stone-200/80 mx-1 self-stretch"></div>
                    <div class="flex-1 pl-2 flex flex-col justify-start">
                        <span class="text-[9px] text-stone-400 font-black uppercase tracking-wider block border-b border-stone-200/60 pb-1 mb-1.5">💧 Oils/Hydr</span>
                        <span id="displayMoistureQty" class="font-black text-stone-900 leading-snug text-[11px] block">--</span>
                    </div>
                </div>
            </div>

            <div class="space-y-2 border-t border-stone-100 pt-3">
                <h5 class="text-[9px] font-black text-stone-400 uppercase tracking-wider">2. Kitchen Preparation & Cooking Instructions</h5>
                <div class="bg-stone-50/40 border border-stone-100 rounded-2xl p-4">
                    <ol id="displayCookingInstructions" class="text-xs text-stone-600 space-y-2 list-decimal list-inside font-medium leading-relaxed">
                        </ol>
                </div>
            </div>

            <div class="bg-orange-50/60 p-4 rounded-2xl border border-orange-100/50 space-y-1.5">
                <h5 class="text-[9px] font-black text-orange-700 uppercase tracking-wider">3. Personal Feeding Allowance</h5>
                <div class="flex justify-between items-baseline">
                    <span class="text-xs font-bold text-stone-700">Calculated Daily Dose Target:</span>
                    <span id="displayDailyTargetIntake" class="text-sm font-black text-stone-900">--</span>
                </div>
                <p class="text-[9px] text-stone-500 leading-normal">
                    *This accurate daily intake volume is scaled from your verified database parameter profile (<span id="displayTargetCaloricBaseline">--</span> kcal baseline).
                </p>
            </div>
        </div>
    @else
        <div class="text-center py-12 px-4 bg-white rounded-3xl border border-dashed border-stone-200 max-w-md mx-auto">
            <span class="text-3xl block mb-2">🐾</span>
            <p class="text-xs font-bold text-stone-400">No profile records calculated yet.</p>
            <p class="text-[10px] text-stone-400/80 mt-0.5">Please evaluate pet parameters in the Calculator tab first!</p>
        </div>
    @endif
</div>

<script>
    const cookbookRotationMatrix = {
        'dog': {
            'regular': {
                'Monday': { 'name': 'Canine Chicken & Thigh Feast', 'density': '260 kcal / cup', 'proteinBasePerKcal': 1.15, 'proteinType': 'Chicken Thighs', 'vegRatio': 0.25, 'vegType': 'Carrots & Peas', 'premixType': 'Canine Regular Premix', 'moisture': '1.5c Broth, Fish Oil', 'calUnits': 260, 'steps': ["Chop raw chicken thighs into bite-sized, easily digestible cubes.", "Steam carrots and peas thoroughly until softened completely.", "Mix meat and cooled greens in a clean kitchen preparation basin.", "Allow batch mixture to cool to room temperature entirely.", "Fold in calculated Holistic Vet Blend powder and healthy oils evenly.", "Portion out daily target containers immediately or freeze."] },
                'Tuesday': { 'name': 'Canine Ground Beef Comfort Dinner', 'density': '270 kcal / cup', 'proteinBasePerKcal': 1.10, 'proteinType': 'Lean Ground Beef', 'vegRatio': 0.20, 'vegType': 'Pumpkin Purée', 'premixType': 'Canine Regular Premix', 'moisture': '2c Water, Fish Oil', 'calUnits': 270, 'steps': ["Brown lean ground beef completely in a pan, draining any excess grease.", "Whisk organic pumpkin purée cleanly with measured fluid bases.", "Thoroughly fold warm beef into the moist pumpkin solution.", "Let the complete batch recipe rest until cool to room temperature.", "Sift required scoops of Vet Blend regular premix and oils cleanly across base.", "Divide safely into customized meal packs or airtight storage bins."] },
                'Wednesday': { 'name': 'Canine Gizzards & Organ Bowl', 'density': '280 kcal / cup', 'proteinBasePerKcal': 1.12, 'proteinType': 'Beef & Gizzards', 'vegRatio': 0.22, 'vegType': 'Broccoli & Carrots', 'premixType': 'Canine Regular Premix', 'moisture': '2c Water, Omega Oil', 'calUnits': 280, 'steps': ["Finely mince organ selections and tough gizzards safely.", "Steam fresh broccoli clusters and carrot spears until mashable.", "Combine protein bits with mashed high-fiber alternatives together.", "Ensure baseline setup has cooled safely down to room parameters.", "Incorporate recommended target regular premix scoops and premium omega oils.", "Label and stack containers nicely inside your refrigerator arrays."] },
                'Thursday': { 'name': 'Canine Chicken & Thigh Feast', 'density': '260 kcal / cup', 'proteinBasePerKcal': 1.15, 'proteinType': 'Chicken Thighs', 'vegRatio': 0.25, 'vegType': 'Carrots & Peas', 'premixType': 'Canine Regular Premix', 'moisture': '1.5c Broth, Fish Oil', 'calUnits': 260, 'steps': ["Cube chicken thigh sections, removing any bone remnants cleanly.", "Simmer peas and diced carrot metrics until soft and fork-tender.", "Mix cooked proteins with warm fiber additions within a large pot.", "Cool the combination down securely before introducing powdered nutrients.", "Blend specific regular supplement requirements along with rich fish oils.", "Secure setup yields directly within custom daily recipe lines."] },
                'Friday': { 'name': 'Canine Ground Beef Comfort Dinner', 'density': '270 kcal / cup', 'proteinBasePerKcal': 1.10, 'proteinType': 'Lean Ground Beef', 'vegRatio': 0.20, 'vegType': 'Pumpkin Purée', 'premixType': 'Canine Regular Premix', 'moisture': '2c Water, Fish Oil', 'calUnits': 270, 'steps': ["Gently skillet-cook lean ground beef over a medium heat source.", "Stir pumpkin purée directly into measured recipe water allocations.", "Incorporate seared beef fields directly across your moisture base.", "Ensure full kitchen mix shifts back down to basic room heat profiles.", "Whisk required regular powder supplement volumes into active yields.", "Pack tightly into calculation batch containers safely."] },
                'Saturday': { 'name': 'Canine Gizzards & Organ Bowl', 'density': '280 kcal / cup', 'proteinBasePerKcal': 1.12, 'proteinType': 'Beef & Gizzards', 'vegRatio': 0.22, 'vegType': 'Broccoli & Carrots', 'premixType': 'Canine Regular Premix', 'moisture': '2c Water, Omega Oil', 'calUnits': 280, 'steps': ["Finely dice or grind meat organ components cleanly.", "Cook broccoli and sweet carrot units via clean steam techniques.", "Blend meats and prepared fibrous mashes thoroughly across a large basin.", "Bring batch heat values down safely past warm parameters.", "Stir targeted regular premix allocations and omega fats in thoroughly.", "Isolate daily tracking lines neatly inside deep storage rows."] },
                'Sunday': { 'name': 'Canine Weekend Chicken Fiesta', 'density': '260 kcal / cup', 'proteinBasePerKcal': 1.15, 'proteinType': 'Chicken Thighs', 'vegRatio': 0.25, 'vegType': 'Carrots & Peas', 'premixType': 'Canine Regular Premix', 'moisture': '1.5c Broth, Fish Oil', 'calUnits': 260, 'steps': ["Process raw poultry thighs into digestible bite-sized fragments.", "Steam standard veggie variables cleanly until translucent and soft.", "Toss active recipe fields inside standard preparation arrays.", "Allow final yield matrices to cool perfectly before continuing.", "Blend targeted supplement portions and premium fish oil completely.", "Package specific batch calculations across organized daily bins."] }
            },
            'limited': {
                'Monday': { 'name': 'Canine Turkey Holiday Bowl', 'density': '255 kcal / cup', 'proteinBasePerKcal': 1.20, 'proteinType': 'Ground Turkey', 'vegRatio': 0.15, 'vegType': 'Carrots & Broccoli', 'premixType': 'Canine Limited Premix', 'moisture': '2c Water, Fish Oil', 'calUnits': 255, 'steps': ["Skillet-cook ground turkey completely until uniform.", "Steam accompanying carrot and broccoli segments soft.", "Combine proteins and processed fiber sets together.", "Cool down mix entirely prior to handling powder elements.", "Incorporate specified limited premix steps and oils safely.", "Portion into calculated delivery formats."] },
                'Tuesday': { 'name': 'Canine Turkey & Rolled Oats', 'density': '255 kcal / cup', 'proteinBasePerKcal': 1.18, 'proteinType': 'Ground Turkey Base', 'vegRatio': 0.18, 'vegType': 'Carrots & Kale', 'premixType': 'Canine Limited Premix', 'moisture': '2.5c Water, Fish Oil', 'calUnits': 255, 'steps': ["Pan-sear ground turkey matrices cleanly.", "Boil or cook fresh kale and sliced carrots until tender.", "Integrate matching protein outputs with fiber additions nicely.", "Ensure core layout drops completely back to room temp scales.", "Fold customized limited vitamin steps across active pots.", "Divide batches into secure daily portions cleanly."] },
                'Wednesday': { 'name': 'Canine Tilapia Vitality Dinner', 'density': '240 kcal / cup', 'proteinBasePerKcal': 1.25, 'proteinType': 'Tilapia Fillets', 'vegRatio': 0.12, 'vegType': 'Mashed Sweet Potato', 'premixType': 'Canine Limited Premix', 'moisture': '1c Broth, Fish Oil', 'calUnits': 240, 'steps': ["Poach fish fillets carefully, running checks for loose bone units.", "Steam or bake clean sweet potato items into soft mush fields.", "Flake whitefish nicely directly across the hot root mash layout.", "Allow your recipe array parameters to match room temperature scales.", "Gently whisk target limited premix portions along with fish oils.", "Store safely inside custom rotation containers."] },
                'Thursday': { 'name': 'Canine Turkey & Super Greens', 'density': '250 kcal / cup', 'proteinBasePerKcal': 1.19, 'proteinType': 'Lean Ground Turkey', 'vegRatio': 0.16, 'vegType': 'Quinoa & Spinach', 'premixType': 'Canine Limited Premix', 'moisture': '2c Water, Omega Oil', 'calUnits': 250, 'steps': ["Simmer ground turkey fields until fully cooked.", "Steam or wilt quinoa and organic spinach layers safely.", "Toss core nutritional segments directly within large pots.", "Let active yields settle loosely down towards room parameters.", "Add targeted limited premix quantities alongside omega additions.", "Freeze any components set aside for extended storage pipelines."] },
                'Friday': { 'name': 'Canine Turkey Holiday Bowl', 'density': '255 kcal / cup', 'proteinBasePerKcal': 1.20, 'proteinType': 'Ground Turkey', 'vegRatio': 0.15, 'vegType': 'Carrots & Broccoli', 'premixType': 'Canine Limited Premix', 'moisture': '2c Water, Fish Oil', 'calUnits': 255, 'steps': ["Brown turkey elements smoothly using standard pans.", "Chop and cook complementary veggie parameters thoroughly.", "Fold components evenly inside processing container layouts.", "Cool the batch format down cleanly before dusting powder inputs.", "Blend structural limited supplement fields evenly across contents.", "Organize into calculated meal spaces correctly."] },
                'Saturday': { 'name': 'Canine Turkey & Rolled Oats', 'density': '255 kcal / cup', 'proteinBasePerKcal': 1.18, 'proteinType': 'Ground Turkey Base', 'vegRatio': 0.18, 'vegType': 'Carrots & Kale', 'premixType': 'Canine Limited Premix', 'moisture': '2.5c Water, Fish Oil', 'calUnits': 255, 'steps': ["Bake turkey recipe blocks thoroughly over medium ranges.", "Puree fiber additions nicely inside mixing systems.", "Combine individual meal aspects together cleanly within rows.", "Cool layout thoroughly to protect essential micronutrient compounds.", "Whisk specified limited supplement metrics evenly into base.", "Store cleanly."] },
                'Sunday': { 'name': 'Canine Tilapia Dinner', 'density': '240 kcal / cup', 'proteinBasePerKcal': 1.25, 'proteinType': 'Tilapia Fillets', 'vegRatio': 0.12, 'vegType': 'Mashed Sweet Potato', 'premixType': 'Canine Limited Premix', 'moisture': '1c Broth, Fish Oil', 'calUnits': 240, 'steps': ["Poach tilapia fillets securely till flake textures appear.", "Boil and mash target orange root elements into pulp profiles.", "Mix whitefish flakes straight inside your moist mash system.", "Ensure components transition fully into standard room setups.", "Stir targeted limited vitamin powder measures and fish oils.", "Serve up fresh or seal tracking arrays."] }
            }
        },
        'cat': {
            'regular': {
                'Monday': { 'name': 'Violet\'s Feline Chicken Pâté', 'density': '35 kcal / oz', 'proteinBasePerKcal': 1.45, 'proteinType': 'Chicken Thighs', 'vegRatio': 0.0, 'vegType': 'Pure Carnivore', 'premixType': 'Feline Premix', 'moisture': '2c Water, Salmon Oil', 'calUnits': 35, 'steps': ["Dice skinless chicken thigh pieces down into ultra-fine minces.", "Gently poach chicken meat fields over medium heat controls.", "Let hot processed protein elements cool down completely to room temp.", "Pour matching liquid metrics and wild salmon oil pools inside cleanly.", "Sift required feline premix scoops softly directly over base.", "Run immersion blenders across mix to forge a silky wet texture."] },
                'Tuesday': { 'name': 'Feline Chicken & Sardine Medley', 'density': '38 kcal / oz', 'proteinBasePerKcal': 1.42, 'proteinType': 'Chicken & Sardines', 'vegRatio': 0.0, 'vegType': 'Pure Carnivore', 'premixType': 'Feline Premix', 'moisture': '1.5c Water, Fish Oil', 'calUnits': 38, 'steps': ["Mince poultry meats finely into uniform bite sizes.", "Clean and flake Atlantic sardine portions, omitting skin/bones.", "Gently poach chicken elements while holding sardine elements raw.", "Cool the multi-protein recipe down towards room standards.", "Dust required feline powder measures right across baseline setups.", "Whip ingredients smoothly into fine wet-food profiles."] },
                'Wednesday': { 'name': 'Feline Turkey & Wild Atlantic Sardine', 'density': '36 kcal / oz', 'proteinBasePerKcal': 1.40, 'proteinType': 'Turkey & Sardines', 'vegRatio': 0.0, 'vegType': 'Pure Carnivore', 'premixType': 'Feline Premix', 'moisture': '2c Turkey Broth', 'calUnits': 36, 'steps': ["Ground clean raw turkey blocks thoroughly.", "Mash whole boneless sardine elements into a dense paste format.", "Warm turkey elements smoothly inside low-sodium broth reservoirs.", "Allow recipe pots to drop all elevated thermal profiles completely.", "Fold essential feline premix scoops across cooled protein mixtures.", "Seal individual jars neatly for weekly organization maps."] },
                'Thursday': { 'name': 'Violet\'s Feline Chicken Pâté', 'density': '35 kcal / oz', 'proteinBasePerKcal': 1.45, 'proteinType': 'Chicken Thighs', 'vegRatio': 0.0, 'vegType': 'Pure Carnivore', 'premixType': 'Feline Premix', 'moisture': '2c Water, Salmon Oil', 'calUnits': 35, 'steps': ["Purée boneless chicken thigh structures into uniform consistency.", "Poach over low flame settings to safely cook while preserving juices.", "Bring batch layout down fully into normal room configurations.", "Whisk designated hydration elements and active salmon oils together.", "Incorporate required feline supplement powders into base.", "Blend completely into a smooth custom pet pâté."] },
                'Friday': { 'name': 'Feline Chicken & Sardine Medley', 'density': '38 kcal / oz', 'proteinBasePerKcal': 1.42, 'proteinType': 'Chicken & Sardines', 'vegRatio': 0.0, 'vegType': 'Pure Carnivore', 'premixType': 'Feline Premix', 'moisture': '1.5c Water, Fish Oil', 'calUnits': 38, 'steps': ["Dice chicken thigh fields cleanly into small bits.", "Flake and prepare whole bone-free sardine cuts neatly.", "Simmer chicken elements over low heat options until fully cooked.", "Cool processed elements back down towards room baselines.", "Stir tracking feline premix calculation steps across setups.", "Portion wet configurations across individual containers."] },
                'Saturday': { 'name': 'Feline Turkey & Wild Atlantic Sardine', 'density': '36 kcal / oz', 'proteinBasePerKcal': 1.40, 'proteinType': 'Turkey & Sardines', 'vegRatio': 0.0, 'vegType': 'Pure Carnivore', 'premixType': 'Feline Premix', 'moisture': '2c Turkey Broth', 'calUnits': 36, 'steps': ["Shred lean turkey cuts into tiny, manageable strands.", "Prep matching flaky sardine elements to discard loose fragments.", "Poach poultry elements inside gentle, clean broth pools safely.", "Cool down the collective formulation back to standard limits.", "Whisk target feline powder requirements cleanly across mix rows.", "Airtight seal all containers before moving to cold spaces."] },
                'Sunday': { 'name': 'Violet\'s Feline Chicken Pâté', 'density': '35 kcal / oz', 'proteinBasePerKcal': 1.45, 'proteinType': 'Chicken Thighs', 'vegRatio': 0.0, 'vegType': 'Pure Carnivore', 'premixType': 'Feline Premix', 'moisture': '2c Water, Salmon Oil', 'calUnits': 35, 'steps': ["Mince fresh chicken variables down into smooth textures.", "Poach pieces over a light simmer; collect natural broth values.", "Cool down active yields securely within your blending bowls.", "Add targeted daily oils and precise liquid metrics evenly.", "Sift required feline supplement steps straight across layouts.", "Stir or pulse into a highly digestible wet paste layout."] }
            },
            'limited': {
                'Monday': { 'name': 'Feline Turkey Florentine Blend', 'density': '34 kcal / oz', 'proteinBasePerKcal': 1.40, 'proteinType': 'Ground Turkey Base', 'vegRatio': 0.01, 'vegType': 'Traces of Spinach', 'premixType': 'Feline Premix', 'moisture': '2c Water, Fish Oil', 'calUnits': 34, 'steps': ["Sauté lean ground turkey over non-stick pan textures safely.", "Steam green spinach shreds finely into thin trace strings.", "Toss turkey elements with active green traces within bowls.", "Let recipe parameters return completely to room temperature scales.", "Incorporate required feline vitamin powders and healthy oils smoothly.", "Divide batch yields out inside structural container series."] },
                'Tuesday': { 'name': 'Feline Turkey & Wild Salmon Mix', 'density': '40 kcal / oz', 'proteinBasePerKcal': 1.48, 'proteinType': 'Turkey & Salmon + Egg', 'vegRatio': 0.0, 'vegType': 'Pure Carnivore', 'premixType': 'Feline Premix', 'moisture': '2c Water, Fish Oil', 'calUnits': 40, 'steps': ["Cook turkey fields gently while flaking wild pink salmon pieces.", "Whisk fresh egg modules and scramble gently alongside meats.", "Integrate all animal proteins within an optimized mixing tray.", "Cool down active components back to standard environment baselines.", "Stir tracked feline premix metrics evenly directly across meat.", "Pack custom calculated wet assets securely within your freezer."] },
                'Wednesday': { 'name': 'Feline Chicken Egg Hash', 'density': '37 kcal / oz', 'proteinBasePerKcal': 1.44, 'proteinType': 'Chicken & 1 Egg', 'vegRatio': 0.01, 'vegType': 'Cooked Mushrooms', 'premixType': 'Feline Premix', 'moisture': '1.5c Water, Fish Oil', 'calUnits': 37, 'steps': ["Mince chicken breasts into ultra-small digestible profiles.", "Scramble egg elements alongside boiled mushroom trace elements.", "Combine poultry bits and fine hash items into preparation pots.", "Cool active matrices thoroughly below hot kitchen standards.", "Whisk necessary feline vitamin properties and fish oils inside.", "Store your custom calculated profiles safely."] },
                'Thursday': { 'name': 'Feline Turkey a La Catnip Treatment', 'density': '33 kcal / oz', 'proteinBasePerKcal': 1.38, 'proteinType': 'Lean Ground Turkey', 'vegRatio': 0.01, 'vegType': 'Catnip Flakes', 'premixType': 'Feline Premix', 'moisture': '2c Water, Fish Oil', 'calUnits': 33, 'steps': ["Pan-cook ground turkey fully until completely opaque.", "Allow cooked meat fields to cool completely to room temperature.", "Stir in your calculated fluids and fish oil pools safely.", "Sift required feline premix scoops across the cooled turkey.", "Dust organic catnip flakes over the mixture as an aroma enhancer.", "Divide immediately into scheduled weekly storage boxes."] },
                'Friday': { 'name': 'Feline Turkey Florentine Blend', 'density': '34 kcal / oz', 'proteinBasePerKcal': 1.40, 'proteinType': 'Ground Turkey Base', 'vegRatio': 0.01, 'vegType': 'Traces of Spinach', 'premixType': 'Feline Premix', 'moisture': '2c Water, Fish Oil', 'calUnits': 34, 'steps': ["Sear ground turkey bases using simple skillet parameters.", "Blanch spinach hints into digestible microscopic accents.", "Mix protein items with green traces inside culinary pans.", "Cool mixture fields completely down towards baseline levels.", "Dust specified feline premix supplements uniformly through space.", "Seal elements up across daily database matching codes."] },
                'Saturday': { 'name': 'Feline Turkey & Wild Salmon Mix', 'density': '40 kcal / oz', 'proteinBasePerKcal': 1.48, 'proteinType': 'Turkey & Salmon + Egg', 'vegRatio': 0.0, 'vegType': 'Pure Carnivore', 'premixType': 'Feline Premix', 'moisture': '2c Water, Fish Oil', 'calUnits': 40, 'steps': ["Pan-cook ground turkey while poaching salmon selections.", "Softly cook egg inclusions into fine granular sizes.", "Toss meat variables inside your main mixing equipment.", "Let the complete batch recipe lower its temperature completely.", "Whisk target feline powder requirements and fish oils cleanly.", "Isolate portions across clean daily refrigerator jars."] },
                'Sunday': { 'name': 'Feline Chicken Egg Hash', 'density': '37 kcal / oz', 'proteinBasePerKcal': 1.44, 'proteinType': 'Chicken & 1 Egg', 'vegRatio': 0.01, 'vegType': 'Cooked Mushrooms', 'premixType': 'Feline Premix', 'moisture': '1.5c Water, Fish Oil', 'calUnits': 37, 'steps': ["Finely shred poultry pieces into short-grain profiles.", "Scramble egg inclusions cleanly with minced mushroom traces.", "Combine meats and hash components inside preparation bowls.", "Cool full meal layouts securely away from stove heat scales.", "Fold specified feline premix powder measures over surfaces.", "Package tracking arrays cleanly within cold storage loops."] }
            }
        }
    };

    let selectedDay = 'Monday';

    function selectRotationDay(element, day) {
        document.querySelectorAll('.weekday-pill').forEach(btn => {
            btn.className = "weekday-pill shrink-0 snap-center px-4 py-2 text-xs font-bold rounded-xl transition bg-white border border-stone-100 text-stone-600 shadow-2xs";
        });
        element.className = "weekday-pill shrink-0 snap-center px-4 py-2 text-xs font-black rounded-xl transition bg-stone-900 text-white border border-stone-900 shadow-2xs shadow-xs";

        selectedDay = day;
        updateWeeklyMealPlan();
    }

    function updateWeeklyMealPlan() {
        const selector = document.getElementById('weeklyPetSelector');
        if(!selector) return;

        const activeOption = selector.options[selector.selectedIndex];
        if(!activeOption) return;

        // 🛠️ JavaScript now pulls the database truth seamlessly from data-type
        const petType = activeOption.getAttribute('data-type') || 'dog';
        const breed = activeOption.getAttribute('data-breed') || 'Mixed Breed';
        const petAge = activeOption.getAttribute('data-age') || '1';
        const petWeight = activeOption.getAttribute('data-weight') || '0';
        const weightUnit = activeOption.getAttribute('data-weight-unit') || 'lbs';
        const blendPreference = activeOption.getAttribute('data-blend-preference') || 'regular';
        const petId = activeOption.value;
        let dailyTargetCalories = parseFloat(activeOption.getAttribute('data-calories')) || 0;

        if(dailyTargetCalories <= 0) {
            dailyTargetCalories = Math.round(petType === 'cat' ? (parseFloat(petWeight) * 32) : (parseFloat(petWeight) * 35));
        }

        document.getElementById('metaBadgeType').innerText = petType === 'cat' ? ' Feline' : ' Canine';
        document.getElementById('metaBadgeBreed').innerText = breed;

        let finalAgeDisplay = petAge.trim();
        const lowerAge = finalAgeDisplay.toLowerCase();
        const numericAgeValue = parseInt(finalAgeDisplay.replace(/[^0-9]/g, '')) || 1;

        if (!lowerAge.includes('month') && !lowerAge.includes('year')) {
            if (lowerAge.includes('puppy') || lowerAge.includes('kitten')) {
                finalAgeDisplay = `${numericAgeValue} Months`;
            } else if (lowerAge.includes('senior')) {
                finalAgeDisplay = `${numericAgeValue} Years`;
            } else {
                finalAgeDisplay = numericAgeValue < 12 ? `${numericAgeValue} Months` : `${Math.round(numericAgeValue / 12)} Years`;
            }
        } else {
            if (lowerAge.includes('month')) finalAgeDisplay = `${numericAgeValue} ${numericAgeValue === 1 ? 'Month' : 'Months'}`;
            if (lowerAge.includes('year')) finalAgeDisplay = `${numericAgeValue} ${numericAgeValue === 1 ? 'Year' : 'Years'}`;
        }

        document.getElementById('cardMetricAge').innerText = finalAgeDisplay;
        document.getElementById('cardMetricWeight').innerText = `${petWeight} ${weightUnit}`;
        document.getElementById('cardMetricCalories').innerText = `${Math.round(dailyTargetCalories)} kcal`;
        document.getElementById('displayTargetCaloricBaseline').innerText = Math.round(dailyTargetCalories);

        const weeklyCaloricLoad = dailyTargetCalories * 7;

        if (cookbookRotationMatrix[petType] && cookbookRotationMatrix[petType][blendPreference] && cookbookRotationMatrix[petType][blendPreference][selectedDay]) {
            let recipeInfo = cookbookRotationMatrix[petType][blendPreference][selectedDay];
            let updatedPremixType = recipeInfo.premixType;
            let finalScoops = 0;

            const hasMonthUnit = finalAgeDisplay.toLowerCase().includes('month');

            if (petType === 'cat') {
                // Ground truth: Felines get the Feline Premix. Period.
                updatedPremixType = 'Feline Premix';
                finalScoops = Math.max(1, Math.round(weeklyCaloricLoad / 250));
            } else {
                if (hasMonthUnit || lowerAge.includes('puppy')) {
                    updatedPremixType = blendPreference === 'limited' ? 'Canine Limited Premix' : 'Canine Puppy Growth Premix';
                } else {
                    updatedPremixType = blendPreference === 'limited' ? 'Canine Limited Premix' : 'Canine Regular Premix';
                }
                finalScoops = Math.max(1, Math.round(weeklyCaloricLoad / 500));
            }

            const rawProteinGrams = Math.round(weeklyCaloricLoad * (recipeInfo.proteinBasePerKcal / 4));
            const rawProteinLbs = (rawProteinGrams / 453.592).toFixed(1);

            let veggieDisplay = recipeInfo.vegType;
            if (recipeInfo.vegRatio > 0) {
                veggieDisplay = `${Math.round(rawProteinGrams * recipeInfo.vegRatio)}g ${recipeInfo.vegType}`;
            }

            document.getElementById('displayRecipeName').innerText = recipeInfo.name;
            document.getElementById('displayRecipeDensity').innerText = recipeInfo.density;

            document.getElementById('displayProteinQty').innerText = `${rawProteinGrams}g (${rawProteinLbs} lbs) ${recipeInfo.proteinType}`;
            document.getElementById('displayCarbGreenQty').innerText = veggieDisplay;
            document.getElementById('displayPremixQty').innerText = `${finalScoops} Scoops (${updatedPremixType})`;
            document.getElementById('displayMoistureQty').innerText = recipeInfo.moisture;

            // 🎯 ADDED: Clear out and build the step list dynamically
            const stepsContainer = document.getElementById('displayCookingInstructions');
            stepsContainer.innerHTML = '';

            if (recipeInfo.steps && recipeInfo.steps.length > 0) {
                recipeInfo.steps.forEach(stepText => {
                    const li = document.createElement('li');
                    li.className = "pl-1";
                    li.innerText = stepText;
                    stepsContainer.appendChild(li);
                });
            } else {
                stepsContainer.innerHTML = "<li class='list-none text-stone-400 italic text-[11px]'>Cook proteins thoroughly, cool completely to room temperature, then stir in supplements.</li>";
            }

            if(petType === 'dog') {
                let cupsPerDay = (dailyTargetCalories / recipeInfo.calUnits).toFixed(1);
                document.getElementById('displayDailyTargetIntake').innerText = `${cupsPerDay} Cups / day`;
            } else {
                let ozPerDay = (dailyTargetCalories / recipeInfo.calUnits).toFixed(1);
                let gramsPerDay = Math.round(ozPerDay * 28.35);
                document.getElementById('displayDailyTargetIntake').innerText = `${ozPerDay} oz (${gramsPerDay}g) / day`;
            }
        }

        document.getElementById('deletePlanForm').action = `/pets/${petId}`;
    }

    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", updateWeeklyMealPlan);
    } else {
        updateWeeklyMealPlan();
    }
</script>
