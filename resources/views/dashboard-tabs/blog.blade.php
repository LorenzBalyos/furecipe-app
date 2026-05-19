<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<style>
    [x-cloak] { display: none !important; }
</style>

<div class="space-y-4 w-full max-w-full overflow-x-hidden" x-data="{ activeArticle: null }">
    <div class="px-1">
        <h2 class="text-xl font-black text-stone-900 tracking-tight">Nutritional Insights</h2>
        <p class="text-xs text-stone-400">Expert guidance on fresh food feeding rules and recipe parameters by Dr. Dody Tyneway.</p>
    </div>

    @php
    $curatedBlogs = [
        [
            'id' => 'article_1',
            'title' => 'Essential Vitamin D for Dogs Benefits You Should Know Introduction',
            'date' => 'Oct 24, 2024',
            'image' => asset('images/blog/Vitamins.png'),
            'excerpt' => 'Switching your companions over to a balanced, real-food diet helps reduce systemic inflammation and keeps digestive tracts clear. Real food offers highly bioavailable vitamins without synthetic manufacturing fillers.',
            'link' => 'https://holisticvetblend.com/blogs/news/essential-vitamin-d-for-dogs-benefits-you-should-know?_pos=3&_sid=416c46119&_ss=r'
        ],
        [
            'id' => 'article_2',
            'title' => 'Raw Food Diet for Pets: Supporting Evidence in Research Evidence Supporting Raw Food Diet for Dogs and Cats',
            'date' => 'Mar 15, 2024',
            'image' => asset('images/blog/PetFood.png'),
            'excerpt' => 'Frequent commercial kibble recalls highlight the vulnerability of highly processed ingredients. Discovering what triggers standard contamination helps pet parents make informed, safer baseline dietary shifts.',
            'link' => 'https://holisticvetblend.com/blogs/news/raw-food-diet-supporting-evidence?_pos=2&_sid=2b6c0f7c1&_ss=r'
        ],
        [
            'id' => 'article_3',
            'title' => 'How to Safely Transition Your Pet to a Fresh Food Diet',
            'date' => 'Jan 10, 2024',
            'image' => asset('images/blog/Nutrition.png'),
            'excerpt' => 'Abrupt nutritional changes can temporarily upset your companion\'s digestion. Learn the optimal step-by-step ratios for smoothly integrating fresh whole ingredients over an intentional 7-to-10 day parameter.',
            'link' => 'https://holisticvetblend.com/blogs/news/how-to-safely-handle-raw-meat-for-your-pets-raw-diet?_pos=5&_sid=2b6c0f7c1&_ss=r'
        ],
        [
            'id' => 'article_4',
            'title' => 'A Beginners Guide to Homemade Cat Food',
            'date' => 'Nov 02, 2023',
            'image' => asset('images/blog/Beggin.png'),
            'excerpt' => 'Deciphering standard commercial metrics, dynamic guaranteed analysis parameters, and ingredient listings can be complex. Learn to easily isolate high-value nutrition components from clever corporate marketing terminology.',
            'link' => 'https://holisticvetblend.com/blogs/news/how-to-make-homemade-cat-food?_pos=10&_sid=78bcdeb49&_ss=r'
        ],
        [
            'id' => 'article_5',
            'title' => 'Easy Homemade Ground Turkey Cat Food Recipe',
            'date' => 'Aug 18, 2023',
            'image' => asset('images/blog/Easy.png'),
            'excerpt' => 'Feeding fresh meat and vegetables alone can induce underlying macro-nutrient deficiencies over time. Balancing accurate calcium, trace mineral, and vitamin ratios ensures true target compliance and cellular vitality.',
            'link' => 'https://holisticvetblend.com/blogs/news/easy-homemade-ground-turkey-cat-food-recipe?_pos=16&_sid=78bcdeb49&_ss=r'
        ],
        [
            'id' => 'article_6',
            'title' => 'Homemade Dog Food for Skin Allergies: Expert Guide',
            'date' => 'Jun 05, 2023',
            'image' => asset('images/blog/Expert.png'),
            'excerpt' => 'Many commercial treats are packed with hidden sugars, high sodium configurations, and artificial colors. Discover fresh, single-ingredient treat solutions that keep your pet highly motivated and perfectly fit.',
            'link' => 'https://holisticvetblend.com/blogs/news/homemade-dog-food-for-skin-allergies-expert-guide?_pos=21&_sid=78bcdeb49&_ss=r'
        ],
        [
            'id' => 'article_7',
            'title' => 'Homemade Dog Food Recipe for Skin Allergies (Vet-Approved)',
            'date' => 'Apr 22, 2023',
            'image' => asset('images/blog/Doggy.png'),
            'excerpt' => 'Managing renal complications requires precise moisture preservation and strategic phosphorus boundaries. A customized, real-food diet helps protect operational kidney function while maintaining lean muscular bulk.',
            'link' => 'https://holisticvetblend.com/blogs/news/homemade-dog-food-recipe-for-skin-allergies-vet-approved?_pos=19&_sid=78bcdeb49&_ss=r'
        ]
    ];
    @endphp

    <div class="space-y-4 w-full">
        @forelse($curatedBlogs as $post)
        <div @click="activeArticle = '{{ $post['id'] }}'" class="w-full bg-white p-4 rounded-2xl border border-stone-200 shadow-sm flex flex-col justify-between space-y-3 box-border cursor-pointer transition hover:border-orange-200 active:scale-[0.99]">
            <div class="w-full min-w-0 wrap-break-words">

                <div class="flex justify-between items-center mb-2.5 gap-2">
                    <span class="text-[9px] bg-orange-50 text-orange-700 border border-orange-100 font-black uppercase tracking-wider px-2 py-0.5 rounded-md shrink-0">Holistic Health</span>
                    <span class="text-[9px] text-stone-400 font-semibold shrink-0">{{ $post['date'] }}</span>
                </div>

                <div class="flex gap-3 items-start">
                    <div class="w-14 h-14 bg-stone-100 border border-stone-200/60 rounded-xl overflow-hidden flex items-center justify-center shrink-0 shadow-2xs">
                        <img src="{{ $post['image'] }}" alt="Blog illustration" class="w-full h-full object-cover" onerror="this.onerror=null; this.parentElement.innerHTML='🐾';">
                    </div>

                    <div class="flex-1 min-w-0">
                        <h4 class="font-black text-xs text-stone-900 leading-snug mb-1 wrap-break-word">
                            {{ $post['title'] }}
                        </h4>
                        <p class="text-[10px] text-stone-500 leading-relaxed text-justify line-clamp-2">
                            {{ $post['excerpt'] }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="pt-2 border-t border-stone-100 flex justify-between items-center w-full" @click.stop>
                <button type="button" @click="activeArticle = '{{ $post['id'] }}'" class="text-[9px] font-black text-orange-600 uppercase tracking-wider focus:outline-hidden">
                    View Summary
                </button>
                <a href="{{ $post['link'] }}" target="_blank" rel="noopener noreferrer" class="text-[9px] font-black text-stone-900 hover:text-orange-600 transition flex items-center gap-1 uppercase tracking-wider focus:outline-hidden">
                    Read Full Article <span class="text-xs">→</span>
                </a>
            </div>
        </div>

        <div x-show="activeArticle === '{{ $post['id'] }}'" x-cloak class="fixed inset-0 bg-stone-900/60 backdrop-blur-xs z-50 flex items-center justify-center p-4" @click.self="activeArticle = null">
            <div class="bg-white rounded-3xl max-w-xs w-full max-h-[75vh] overflow-y-auto border border-stone-200 shadow-2xl flex flex-col animate-in fade-in zoom-in-95 duration-150">

                <div class="sticky top-0 bg-white px-4 py-3 border-b border-stone-100 flex justify-between items-center z-10">
                    <span class="text-[9px] text-orange-600 font-black uppercase tracking-widest">Summary Reader</span>
                    <button type="button" @click="activeArticle = null" class="w-6 h-6 bg-stone-100 text-stone-700 rounded-full flex items-center justify-center font-bold text-[10px] focus:outline-hidden">✕</button>
                </div>

                <div class="w-full h-32 bg-stone-50 border-b border-stone-100 overflow-hidden flex items-center justify-center">
                    <img src="{{ $post['image'] }}" alt="Full banner view" class="w-full h-full object-cover" onerror="this.onerror=null; this.style.display='none';">
                </div>

                <div class="p-4 space-y-3 wrap-break-words w-full text-xs">
                    <span class="text-[9px] text-stone-400 font-bold block">{{ $post['date'] }} • Dr. Dody Tyneway</span>
                    <h3 class="font-black text-sm text-stone-900 leading-tight tracking-tight w-full">{{ $post['title'] }}</h3>
                    <div class="w-8 h-0.5 bg-orange-500 rounded-full"></div>

                    <p class="text-[11px] text-stone-600 leading-relaxed text-justify whitespace-pre-line w-full">
                        {{ $post['excerpt'] }} Switching over to a balanced, real-food diet helps reduce systemic inflammation and keeps digestive tracts clear. Incorporating these tailored whole ingredients ensures a clean approach that optimizes target wellness without common commercial manufacturing fillers.
                    </p>
                </div>

                <div class="p-4 bg-stone-50 border-t border-stone-100 mt-auto">
                    <a href="{{ $post['link'] }}" target="_blank" rel="noopener noreferrer" class="w-full bg-stone-900 hover:bg-stone-800 text-white font-bold text-center block py-2.5 rounded-xl transition text-[10px] uppercase tracking-wider">
                        Open Full Article Website 🌐
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white p-6 rounded-2xl border border-stone-200 text-center w-full">
            <p class="text-xs text-stone-400">No entries recorded in feed stream.</p>
        </div>
        @endforelse
    </div>
</div>
