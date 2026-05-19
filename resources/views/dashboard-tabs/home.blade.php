<div>
    <div class="relative p-5 rounded-3xl text-stone-900 mb-5 shadow-xs overflow-hidden border border-stone-200/40 bg-stone-100">

        <div class="absolute inset-0 z-0 bg-cover bg-center filter blur-xs opacity-25 scale-105"
             style="background-image: url('{{ asset('images/bg.png') }}');">
        </div>

        <div class="relative z-10">
            <span class="text-[9px] uppercase font-black tracking-widest bg-orange-600 text-white px-2 py-0.5 rounded-full">Daily Insight</span>
            <p class="text-xs font-black leading-relaxed mt-2.5 italic text-stone-900">
                "The finest food you can give your pet is a balanced meal made with fresh, real food variables in your own kitchen."
            </p>
            <span class="block text-[9px] text-stone-500 font-bold mt-1.5 text-right">— Dr. Dody Tyneway, Holistic Vet Blend</span>
        </div>
    </div>

    <div class="bg-white p-4 rounded-2xl border border-stone-200/60 shadow-xs mb-4">
        <h3 class="text-xs font-black uppercase text-stone-400 mb-3 tracking-wider">Workspace Shortcuts</h3>
        <div class="space-y-2">
            <div class="grid grid-cols-2 gap-2">
                <button onclick="switchTab('calculator')" class="bg-stone-50 hover:bg-stone-100 border border-stone-200/40 p-3 rounded-xl text-left transition focus:outline-hidden">
                    <span class="text-lg block mb-1">🧮</span>
                    <strong class="text-xs font-black block text-stone-800">Diet Math</strong>
                    <span class="text-[9px] text-stone-400 block leading-tight">Run nutrient scaling formulas</span>
                </button>
                <button onclick="switchTab('shop')" class="bg-stone-50 hover:bg-stone-100 border border-stone-200/40 p-3 rounded-xl text-left transition focus:outline-hidden">
                    <span class="text-lg block mb-1">🌿</span>
                    <strong class="text-xs font-black block text-stone-800">The Store</strong>
                    <span class="text-[9px] text-stone-400 block leading-tight">Secure premix powders</span>
                </button>
            </div>

            <div class="grid grid-cols-2 gap-2">
                <a href="{{ asset('cookbooks/cat-recipes.pdf') }}" target="_blank" class="bg-stone-50 hover:bg-stone-100 border border-stone-200/40 p-3 rounded-xl text-left transition block no-underline">
                    <span class="text-lg block mb-1">🐱</span>
                    <strong class="text-xs font-black block text-stone-800">Cat Cookbook</strong>
                    <span class="text-[9px] text-stone-400 block leading-tight">Favorite recipes (PDF)</span>
                </a>
                <a href="{{ asset('cookbooks/dog-recipes.pdf') }}" target="_blank" class="bg-stone-50 hover:bg-stone-100 border border-stone-200/40 p-3 rounded-xl text-left transition block no-underline">
                    <span class="text-lg block mb-1">🐶</span>
                    <strong class="text-xs font-black block text-stone-800">Dog Cookbook</strong>
                    <span class="text-[9px] text-stone-400 block leading-tight">Favorite recipes (PDF)</span>
                </a>
            </div>
        </div>
    </div>


    <div class="bg-white p-4 rounded-2xl border border-stone-200/60 shadow-xs">
        <div class="flex justify-between items-center mb-3">
            <h3 class="text-xs font-black uppercase text-stone-400 tracking-wider">Success Testimonials</h3>
            <a href="https://holisticvetblend.com/pages/testimonials" target="_blank" rel="noopener noreferrer" class="text-[9px] font-black text-orange-600 uppercase tracking-wider hover:underline">
                View All →
            </a>
        </div>

        <div class="flex gap-3 overflow-x-auto pb-2 snap-x scrollbar-none scroll-smooth -mx-1 px-1">
            <div class="min-w-[85%] max-w-[85%] bg-stone-50 border border-stone-200/50 p-3.5 rounded-xl snap-start flex flex-col justify-between space-y-3">
                <div class="space-y-1.5">
                    <div class="flex justify-between items-center">
                        <span class="text-[8px] bg-red-50 text-red-600 font-black tracking-wider uppercase px-1.5 py-0.5 rounded border border-red-100">🎥 Video Review</span>
                        <span class="text-amber-400 text-[10px] font-bold">⭐⭐⭐⭐⭐</span>
                    </div>
                    <p class="text-[11px] text-stone-600 italic leading-relaxed">
                        "My kitty Mochi has been taking it for a few weeks, and the difference has been amazing! Her energy is fully back."
                    </p>
                </div>
                <div class="flex justify-between items-center pt-2 border-t border-stone-200/40">
                    <span class="text-[9px] font-black text-stone-800">Cassy P. (Mochi's Mom)</span>
                    <a href="https://holisticvetblend.com/pages/testimonials" target="_blank" class="bg-stone-900 text-white font-black text-[8px] uppercase px-2 py-1 rounded-md flex items-center gap-1 shadow-2xs">
                        Watch YouTube 🎬
                    </a>
                </div>
            </div>

            <div class="min-w-[85%] max-w-[85%] bg-stone-50 border border-stone-200/50 p-3.5 rounded-xl snap-start flex flex-col justify-between space-y-3">
                <div class="space-y-1.5">
                    <div class="flex justify-between items-center">
                        <span class="text-[8px] bg-orange-50 text-orange-700 font-black tracking-wider uppercase px-1.5 py-0.5 rounded border border-orange-100">🐾 Certified Story</span>
                        <span class="text-amber-400 text-[10px] font-bold">⭐⭐⭐⭐⭐</span>
                    </div>
                    <p class="text-[11px] text-stone-600 italic leading-relaxed">
                        "My dog Pepper has been taking this customized holistic supplement blend for over four years with beautiful lab values."
                    </p>
                </div>
                <div class="flex justify-between items-center pt-2 border-t border-stone-200/40">
                    <span class="text-[9px] font-black text-stone-800">Pepper's Family</span>
                    <span class="text-[9px] text-stone-400 font-bold">Verified Client</span>
                </div>
            </div>
        </div>
    </div>
</div>
