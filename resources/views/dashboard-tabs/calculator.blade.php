<div class="bg-white p-5 rounded-3xl border border-stone-200/60 shadow-sm">
    <div class="mb-4">
        <h2 class="text-base font-black text-stone-900 leading-tight">Pet Profile Calculator</h2>
        <p class="text-[11px] text-stone-400">Design a specialized target recipe program for your pet companion.</p>

        <div class="flex gap-1 mt-3">
            <div id="bar-step-1" class="h-1 flex-1 bg-orange-600 rounded-full transition-colors duration-200"></div>
            <div id="bar-step-2" class="h-1 flex-1 bg-stone-100 rounded-full transition-colors duration-200"></div>
            <div id="bar-step-3" class="h-1 flex-1 bg-stone-100 rounded-full transition-colors duration-200"></div>
            <div id="bar-step-4" class="h-1 flex-1 bg-stone-100 rounded-full transition-colors duration-200"></div>
        </div>
    </div>

    <form id="petCalculatorForm" action="/calculator/compute" method="POST">
        @csrf

        <div id="form-step-1" class="wizard-step space-y-4">
            <div>
                <label class="block text-[10px] font-black uppercase text-stone-400 tracking-wider mb-2">1. Select Companion Species</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="border-2 border-orange-600 rounded-2xl p-4 flex flex-col items-center justify-center cursor-pointer transition bg-orange-50/30 text-center relative check-card" id="card-species-dog">
                        <input type="radio" name="species" value="dog" checked class="hidden" onchange="handleSpeciesChange('dog')">
                        <span class="text-2xl mb-1">🐕</span>
                        <strong class="text-xs font-black block text-stone-800">Canine</strong>
                    </label>
                    <label class="border border-stone-200 rounded-2xl p-4 flex flex-col items-center justify-center cursor-pointer transition text-center relative check-card" id="card-species-cat">
                        <input type="radio" name="species" value="cat" class="hidden" onchange="handleSpeciesChange('cat')">
                        <span class="text-2xl mb-1">🐈</span>
                        <strong class="text-xs font-black block text-stone-800">Feline</strong>
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-black uppercase text-stone-400 tracking-wider mb-1.5">2. Companion Identity Name</label>
                <input type="text" name="pet_name" id="calc_name" required placeholder="e.g., Mochi, Pepper" class="w-full bg-stone-50 border border-stone-200 rounded-xl px-4 py-3 text-xs font-semibold focus:outline-orange-500">
            </div>

            <div>
                <label class="block text-[10px] font-black uppercase text-stone-400 tracking-wider mb-2">3. Biological Gender Matrix</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="border-2 border-orange-600 rounded-xl py-2.5 flex items-center justify-center gap-2 cursor-pointer transition bg-orange-50/30 text-xs font-black text-stone-800 gender-card" id="card-gender-boy">
                        <input type="radio" name="gender" value="Boy" checked class="hidden" onchange="handleGenderChange('Boy')">
                        <span>♂️</span> Boy
                    </label>
                    <label class="border border-stone-200 rounded-xl py-2.5 flex items-center justify-center gap-2 cursor-pointer transition text-xs font-black text-stone-500 gender-card" id="card-gender-girl">
                        <input type="radio" name="gender" value="Girl" class="hidden" onchange="handleGenderChange('Girl')">
                        <span>♀️</span> Girl
                    </label>
                </div>
            </div>
        </div>

        <div id="form-step-2" class="wizard-step hidden space-y-4">
            <div>
                <label class="block text-[10px] font-black uppercase text-stone-400 tracking-wider mb-1.5">4. Structural Breed Specification</label>
                <select name="breed" id="calc_breed" class="w-full bg-stone-50 border border-stone-200 rounded-xl px-4 py-3 text-xs font-semibold focus:outline-orange-500"></select>
            </div>

            <div>
                <label class="block text-[10px] font-black uppercase text-stone-400 tracking-wider mb-1.5">5. Lifespan Age Matrix Span</label>
                <select name="age_value" id="calc_age" class="w-full bg-stone-50 border border-stone-200 rounded-xl px-4 py-3 text-xs font-semibold focus:outline-orange-500">
                    <option value="3">3 Months</option>
                    <option value="4">4 Months</option>
                    <option value="5">5 Months</option>
                    <option value="6">6 Months</option>
                    <option value="7">7 Months</option>
                    <option value="8">8 Months</option>
                    <option value="9">9 Months</option>
                    <option value="10">10 Months</option>
                    <option value="11">11 Months</option>
                    <option value="12">12 Months (1 Year Old)</option>
                    <option value="24">24 Months (2 Years Old)</option>
                    <option value="36">36 Months (3 Years Old)</option>
                    <option value="48">48 Months (4 Years Old)</option>
                    <option value="60">60 Months (5 Years Old)</option>
                    <option value="72">72 Months (6 Years Old)</option>
                    <option value="84">84 Months (7 Years Old)</option>
                    <option value="96">96 Months (8 Years Old)</option>
                    <option value="108">108 Months (9 Years Old)</option>
                    <option value="120">120 Months (10 Years Old)</option>
                    <option value="132">132 Months (11 Years Old)</option>
                    <option value="144">144 Months (12 Years Old)</option>
                    <option value="156">156 Months (13 Years Old)</option>
                    <option value="168">168 Months (14 Years Old)</option>
                    <option value="180">180 Months (15 Years Old)</option>
                    <option value="192">192 Months (16 Years Old)</option>
                    <option value="204">204 Months (17 Years Old)</option>
                    <option value="216">216 Months (18 Years Old)</option>
                    <option value="228">228 Months (19 Years Old)</option>
                    <option value="240">240 Months (20 Years Old)</option>
                </select>
                <input type="hidden" name="age_unit" value="Months">
            </div>
        </div>

        <div id="form-step-3" class="wizard-step hidden space-y-4">
            <div class="grid grid-cols-3 gap-3 items-end">
                <div class="col-span-2">
                    <label class="block text-[10px] font-black uppercase text-stone-400 tracking-wider mb-1.5">6. Current Weight Status</label>
                    <input type="number" step="0.1" name="weight" id="calc_weight" required placeholder="12" class="w-full bg-stone-50 border border-stone-200 rounded-xl px-4 py-3 text-xs font-semibold focus:outline-orange-500">
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase text-stone-400 tracking-wider mb-1.5">Scale Metric</label>
                    <div class="w-full bg-stone-100 border border-stone-200 rounded-xl px-4 py-3 text-xs font-bold text-stone-500 text-center select-none">
                        Pounds (lbs)
                    </div>
                    <input type="hidden" name="weight_unit" id="calc_weight_unit" value="lbs">
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-black uppercase text-stone-400 tracking-wider mb-2">7. Body Condition Score Target Alignment</label>
                <div class="grid grid-cols-3 gap-2">
                    <label class="border border-stone-200 rounded-xl p-3 text-center cursor-pointer transition block bcs-card" id="card-bcs-underweight">
                        <input type="radio" name="body_condition" value="Underweight" class="hidden" onchange="handleBCSChange('Underweight')">
                        <span class="text-base block">🦴</span>
                        <strong class="text-[10px] font-black block mt-1 text-stone-700">Underweight</strong>
                    </label>
                    <label class="border-2 border-orange-600 bg-orange-50/30 rounded-xl p-3 text-center cursor-pointer transition block bcs-card" id="card-bcs-ideal">
                        <input type="radio" name="body_condition" value="Ideal Weight" checked class="hidden" onchange="handleBCSChange('Ideal Weight')">
                        <span class="text-base block">✨</span>
                        <strong class="text-[10px] font-black block mt-1 text-stone-800">Ideal Weight</strong>
                    </label>
                    <label class="border border-stone-200 rounded-xl p-3 text-center cursor-pointer transition block bcs-card" id="card-bcs-overweight">
                        <input type="radio" name="body_condition" value="Overweight" class="hidden" onchange="handleBCSChange('Overweight')">
                        <span class="text-base block">🪵</span>
                        <strong class="text-[10px] font-black block mt-1 text-stone-700">Overweight</strong>
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-black uppercase text-stone-400 tracking-wider mb-2">8. Routine Lifestyle Activity Metrics</label>
                <div class="space-y-2">
                    <label class="flex items-start gap-3 p-3 border border-stone-200 rounded-xl cursor-pointer transition hover:bg-stone-50/60 activity-row bg-white" id="row-act-less">
                        <input type="radio" name="activity_level" value="Less Active" class="mt-0.5 accent-orange-600" onchange="handleActivityChange('less')">
                        <div class="leading-tight">
                            <strong class="text-xs font-black text-stone-800 block">Less Active</strong>
                            <span class="text-[10px] text-stone-400">Not much more than naps</span>
                        </div>
                    </label>
                    <label class="flex items-start gap-3 p-3 border-2 border-orange-600 rounded-xl cursor-pointer transition hover:bg-stone-50/60 activity-row bg-orange-50/20" id="row-act-semi">
                        <input type="radio" name="activity_level" value="Semi Active" checked class="mt-0.5 accent-orange-600" onchange="handleActivityChange('semi')">
                        <div class="leading-tight">
                            <strong class="text-xs font-black text-stone-800 block">Semi Active</strong>
                            <span class="text-[10px] text-stone-400">A couple walks per week</span>
                        </div>
                    </label>
                    <label class="flex items-start gap-3 p-3 border border-stone-200 rounded-xl cursor-pointer transition hover:bg-stone-50/60 activity-row bg-white" id="row-act-active">
                        <input type="radio" name="activity_level" value="Active" class="mt-0.5 accent-orange-600" onchange="handleActivityChange('active')">
                        <div class="leading-tight">
                            <strong class="text-xs font-black text-stone-800 block">Active</strong>
                            <span class="text-[10px] text-stone-400">Daily walks and playtime</span>
                        </div>
                    </label>
                    <label class="flex items-start gap-3 p-3 border border-stone-200 rounded-xl cursor-pointer transition hover:bg-stone-50/60 activity-row bg-white" id="row-act-highly">
                        <input type="radio" name="activity_level" value="Highly Active" class="mt-0.5 accent-orange-600" onchange="handleActivityChange('highly')">
                        <div class="leading-tight">
                            <strong class="text-xs font-black text-stone-800 block">Highly Active</strong>
                            <span class="text-[10px] text-stone-400">Daily runs and sports</span>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        <div id="form-step-4" class="wizard-step hidden space-y-4">
            <div>
                <label class="block text-[10px] font-black uppercase text-stone-400 tracking-wider mb-1.5">9. Daily Routine Feeding Frequency</label>
                <select name="feeding_frequency" id="calc_freq" class="w-full bg-stone-50 border border-stone-200 rounded-xl px-4 py-3 text-xs font-semibold focus:outline-orange-500">
                    <option value="1 time a day">1 time a day</option>
                    <option value="2 times a day" selected>2 times a day (Standard)</option>
                    <option value="3 times a day">3 times a day</option>
                    <option value="4 times a day">4 times a day</option>
                </select>
            </div>

            <div class="bg-[#FDF7F2] p-4 rounded-2xl border border-stone-200/60 space-y-2.5">
                <h3 class="text-[10px] font-black text-orange-700 uppercase tracking-widest border-b border-orange-200/50 pb-1">Review Parameters Manifest</h3>
                <div class="grid grid-cols-2 gap-x-2 gap-y-2 text-xs">
                    <div><span class="text-[9px] text-stone-400 block uppercase">Profile Target</span><strong id="summary-name-species" class="text-stone-800">-</strong></div>
                    <div><span class="text-[9px] text-stone-400 block uppercase">Gender Biological</span><strong id="summary-gender" class="text-stone-800">-</strong></div>
                    <div><span class="text-[9px] text-stone-400 block uppercase">Structural Breed</span><strong id="summary-breed" class="text-stone-800">-</strong></div>
                    <div><span class="text-[9px] text-stone-400 block uppercase">Calculated Age</span><strong id="summary-age" class="text-stone-800">-</strong></div>
                    <div><span class="text-[9px] text-stone-400 block uppercase">Current Weight</span><strong id="summary-weight" class="text-stone-800">-</strong></div>
                    <div><span class="text-[9px] text-stone-400 block uppercase">Condition Score</span><strong id="summary-bcs" class="text-stone-800">-</strong></div>
                    <div class="col-span-2"><span class="text-[9px] text-stone-400 block uppercase">Selected Activity Metric</span><strong id="summary-activity" class="text-stone-800">-</strong></div>
                    <div class="col-span-2"><span class="text-[9px] text-stone-400 block uppercase">Feeding Routine Frequency</span><strong id="summary-freq" class="text-stone-800">-</strong></div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between pt-4 mt-4 border-t border-stone-100 gap-3">
            <button type="button" id="wizardBackBtn" onclick="stepTransition(-1)" class="bg-stone-100 hover:bg-stone-200 text-stone-800 text-xs font-black uppercase tracking-wider py-3 px-5 rounded-xl transition invisible">
                Back
            </button>
            <button type="button" id="wizardNextBtn" onclick="stepTransition(1)" class="flex-1 bg-stone-900 hover:bg-orange-600 text-white text-xs font-black uppercase tracking-wider py-3 rounded-xl text-center transition shadow-xs">
                Continue →
            </button>
        </div>
    </form>
</div>

<script>
    // System Structural Data Lists
    const internalBreedsEngine = {
        dog: [
            "Shih Tzu", "Pug", "Chihuahua", "Golden Retriever", "German Shepherd",
            "French Bulldog", "Poodle", "Labrador Retriever", "Beagle", "Boxer", "Mixed Breed (Askal/Aspin)"
        ],
        cat: [
            "Persian", "Siamese", "Maine Coon", "Ragdoll", "British Shorthair",
            "Abyssinian", "Sphynx", "Bengal", "American Shorthair", "Mixed Breed (Puspin)"
        ]
    };

    let activeWizardStep = 1;

    function populateBreedDataset(species) {
        const dropdown = document.getElementById('calc_breed');
        if(!dropdown) return;
        dropdown.innerHTML = '';
        internalBreedsEngine[species].forEach(breed => {
            let item = document.createElement('option');
            item.value = breed;
            item.innerText = breed;
            dropdown.appendChild(item);
        });
    }

    function handleSpeciesChange(species) {
        document.querySelectorAll('.check-card').forEach(c => c.classList.replace('border-2', 'border'));
        document.querySelectorAll('.check-card').forEach(c => c.classList.replace('border-orange-600', 'border-stone-200'));
        document.querySelectorAll('.check-card').forEach(c => c.classList.remove('bg-orange-50/30'));

        const target = document.getElementById('card-species-' + species);
        target.classList.replace('border', 'border-2');
        target.classList.replace('border-stone-200', 'border-orange-600');
        target.classList.add('bg-orange-50/30');
        populateBreedDataset(species);
    }

    function handleGenderChange(genderId) {
        document.querySelectorAll('.gender-card').forEach(c => {
            c.classList.replace('border-2', 'border');
            c.classList.replace('border-orange-600', 'border-stone-200');
            c.classList.replace('text-stone-800', 'text-stone-500');
            c.classList.remove('bg-orange-50/30');
        });
        const target = document.getElementById('card-gender-' + genderId.toLowerCase());
        target.classList.replace('border', 'border-2');
        target.classList.replace('border-stone-200', 'border-orange-600');
        target.classList.replace('text-stone-500', 'text-stone-800');
        target.classList.add('bg-orange-50/30');
    }

    function handleBCSChange(condition) {
        document.querySelectorAll('.bcs-card').forEach(c => {
            c.classList.replace('border-2', 'border');
            c.classList.replace('border-orange-600', 'border-stone-200');
            c.classList.replace('text-stone-800', 'text-stone-700');
            c.classList.remove('bg-orange-50/30');
        });
        let searchId = 'card-bcs-' + (condition === 'Ideal Weight' ? 'ideal' : condition.toLowerCase());
        const target = document.getElementById(searchId);
        target.classList.replace('border', 'border-2');
        target.classList.replace('border-stone-200', 'border-orange-600');
        target.classList.replace('text-stone-700', 'text-stone-800');
        target.classList.add('bg-orange-50/30');
    }

    function handleActivityChange(key) {
        document.querySelectorAll('.activity-row').forEach(r => {
            r.classList.replace('border-2', 'border');
            r.classList.replace('border-orange-600', 'border-stone-200');
            r.classList.remove('bg-orange-50/20');
            r.classList.add('bg-white');
        });
        const target = document.getElementById('row-act-' + key);
        target.classList.replace('border', 'border-2');
        target.classList.replace('border-stone-200', 'border-orange-600');
        target.classList.remove('bg-white');
        target.classList.add('bg-orange-50/20');
    }

    function compileSummarySheet() {
        const name = document.getElementById('calc_name').value || 'Unnamed Pet';
        const speciesValue = document.querySelector('input[name="species"]:checked').value;
        const icon = speciesValue === 'dog' ? '🐶' : '🐈';

        const ageDropdown = document.getElementById('calc_age');
        const ageText = ageDropdown.options[ageDropdown.selectedIndex].text;

        document.getElementById('summary-name-species').innerText = `${icon} ${name} (${speciesValue.toUpperCase()})`;
        document.getElementById('summary-gender').innerText = document.querySelector('input[name="gender"]:checked').value;
        document.getElementById('summary-breed').innerText = document.getElementById('calc_breed').value;
        document.getElementById('summary-age').innerText = ageText;
        document.getElementById('summary-weight').innerText = `${document.getElementById('calc_weight').value} lbs`;
        document.getElementById('summary-bcs').innerText = document.querySelector('input[name="body_condition"]:checked').value;
        document.getElementById('summary-activity').innerText = document.querySelector('input[name="activity_level"]:checked').value;
        document.getElementById('summary-freq').innerText = document.getElementById('calc_freq').value;
    }

    function stepTransition(delta) {
        if(delta === 1) {
            if(activeWizardStep === 1 && !document.getElementById('calc_name').value.trim()) {
                alert('Please register your pet identity name before continuing.');
                return;
            }
            if(activeWizardStep === 2 && !document.getElementById('calc_age').value) {
                alert('Please designate your pet age parameters.');
                return;
            }
            if(activeWizardStep === 3 && !document.getElementById('calc_weight').value) {
                alert('Please declare a true calculated numeric weight value.');
                return;
            }
        }

        document.getElementById('form-step-' + activeWizardStep).classList.add('hidden');
        document.getElementById('bar-step-' + activeWizardStep).classList.replace('bg-orange-600', 'bg-stone-100');

        activeWizardStep += delta;

        document.getElementById('form-step-' + activeWizardStep).classList.remove('hidden');
        document.getElementById('bar-step-' + activeWizardStep).classList.replace('bg-stone-100', 'bg-orange-600');

        const backBtn = document.getElementById('wizardBackBtn');
        const nextBtn = document.getElementById('wizardNextBtn');

        if(activeWizardStep === 1) { backBtn.classList.add('invisible'); } else { backBtn.classList.remove('invisible'); }

        if(activeWizardStep === 4) {
            compileSummarySheet();
            nextBtn.innerText = "Formulate Meal Plan ✔";
            nextBtn.setAttribute('onclick', "document.getElementById('petCalculatorForm').submit();");
        } else {
            nextBtn.innerText = "Continue →";
            nextBtn.setAttribute('onclick', "stepTransition(1)");
        }
    }

    // Initialize Breed Dataset Option Sets
    populateBreedDataset('dog');
</script>
