<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Google\Cloud\Firestore\FirestoreClient;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\ProfileController;

/**
 * Initialize connection with Google Cloud Firestore NoSQL Database
 * Gracefully handles Render environments and local fallbacks smoothly.
 */
function getFirestore() {
    // 1. Force Render to bypass ALL local file checks entirely
    if (isset($_SERVER['RENDER']) || env('APP_ENV') === 'production' || !empty(env('FIREBASE_JSON'))) {
        return new FirestoreClient([
            'projectId' => 'furecipe',
            'transport' => 'rest',
            'keyFile' => [
                'type'                        => 'service_account',
                'project_id'                  => 'furecipe',
                'private_key_id'              => 'b0c5e9888d72c64c75e0dba512325a1417fcefa6',
                'private_key'                 => '-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQCmK7u8YvWc68u6\n7L1pAexmZf38YIeOa98zYtFkR8w96PzX05P1V8WcQWb9z74D8Y88A58X2W38vG9w\nK9G5z2C8D6aR7n8Qy7z9yv9O8X8W38vG9wK9G5z2C8D6aR7n8Qy7z9yv9O8X8W38\nvG9wK9G5z2C8D6aR7n8Qy7z9yv9O8X8W38vG9wK9G5z2C8D6aR7n8Qy7z9yv9O8\nX8W38vG9wK9G5z2C8D6aR7n8Qy7z9yv9O8X8W38vG9wK9G5z2C8D6aR7n8Qy7z9\nyv9O8X8W38vG9wK9G5z2C8D6aR7n8Qy7z9yv9O8X8W38vG9wK9G5z2C8D6aR7n8\nQy7z9yv9O8X8W38vG9wK9G5z2C8D6aR7n8Qy7z9yv9O8X8W38vG9wK9G5z2C8D6\naR7n8Qy7z9yv9O8X8W38vG9wK9G5z2C8D6aR7n8Qy7z9yv9O8X8W38vG9wK9G5z\n2C8D6aR7n8Qy7z9yv9O8X8W38vG9wK9G5z2C8D6aR7n8Qy7z9yv9O8X8W38vG9w\nK9G5z2C8D6aR7n8Qy7z9yv9O8X8W38vG9wK9G5z2C8D6aR7n8Qy7z9yv9O8X8W3\n8vG9wK9G5z2C8D6aR7n8Qy7z9yv9O8X8W38vG9wK9G5z2C8D6aR7n8Qy7z9yv9O\n8X8W38vG9wK9G5z2C8D6aR7n8Qy7z9yv9O8X8W38vG9wK9G5z2C8D6aR7n8Qy7z\n9yv9O8X8W38vG9wK9G5z2C8D6aR7n8Qy7z9yv9O8X8W38vG9wK9G5z2C8D6aR7n\n8Qy7z9yv9O8X8W38vG9wK9G5z2C8D6aR7n8Qy7z9yv9O8X8W38vG9wK9G5z2C8D\n6aR7n8Qy7z9yv9O8X8W38vG9wK9G5z2C8D6aR7n8Qy7z9yv9O8X8W38vG9wK9G5\nz2C8D6aR7n8Qy7z9yv9O8X8W38vG9wK9G5z2C8D6aR7n8Qy7z9yv9O8X8W38vG9\nwK9G5z2C8D6aR7n8Qy7z9yv9O8X8W38vG9wK9G5z2C8D6aR7n8Qy7z9yv9O8X8W\n38vG9wK9G5z2C8D6aR7n8Qy7z9yv9O8X8W38vG9wK9G5z2C8D6aR7n8Qy7z9yv9\nO8X8W38vG9wK9G5z2C8D6aR7n8Qy7z9yv9O8X8W38vG9wK9G5z2C8D6aR7n8Qy7\nz9yv9O8X8W38vG9wK9G5z2C8D6aR7n8Qy7z9yv9O8X8W38vG9wK9G5z2C8D6aR7\nn8Qy7z9yv9O8X8W38vG9wK9G5z2C8SStvsh39sfSks92Sksjdhwshd9wshd9w\n-----END PRIVATE KEY-----\n',
                'client_email'               => 'firebase-adminsdk-pmed8@furecipe.iam.gserviceaccount.com',
                'client_id'                  => '105581559812920235372',
                'auth_uri'                    => 'https://accounts.google.com/o/oauth2/auth',
                'token_uri'                   => 'https://oauth2.googleapis.com/token',
                'auth_provider_x509_cert_url' => 'https://www.googleapis.com/oauth2/v1/certs',
                'client_x509_cert_url'        => 'https://www.googleapis.com/robot/v1/metadata/x509/firebase-adminsdk-pmed8%40furecipe.iam.gserviceaccount.com'
            ]
        ]);
    }

    // 2. Local Fallback Only
    $path = storage_path('firebase_credentials.json');
    if (!file_exists($path)) {
         $path = storage_path('app/firebase_credentials.json');
    }
    if (file_exists($path)) {
        $fileConfig = json_decode(file_get_contents($path), true);
        if (is_array($fileConfig)) {
            return new FirestoreClient([
                'projectId' => $fileConfig['project_id'] ?? 'furecipe',
                'keyFile'   => $fileConfig,
                'transport' => 'rest'
            ]);
        }
    }

    return new FirestoreClient([
        'projectId' => 'furecipe',
        'transport' => 'rest'
    ]);
}

// =========================================================================
// 1. PUBLIC MARKETING & WELCOME PAGES
// =========================================================================
Route::get('/', function () {
    if (session()->has('user_email')) {
        return redirect('/dashboard');
    }
    return view('welcome');
});

// =========================================================================
// 2. SECURE AUTHENTICATION FLOW (REAL GOOGLE OAUTH & OTP REGISTER)
// =========================================================================

// --- LOGIN SUB-SYSTEM ---
Route::get('/login', function () {
    if (session()->has('user_email')) return redirect('/dashboard');
    return view('auth.login');
})->name('login');

Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    $firestore = getFirestore();
    $user = $firestore->collection('users')->document($request->email)->snapshot();

    if ($user->exists() && Hash::check($request->password, $user->get('password'))) {
        session(['user_email' => $request->email, 'user_name' => $user->get('name')]);

        // Redirects directly to home instead of shop panel upon login success
        return redirect('/dashboard?tab=home');
    }
    return back()->withErrors(['email' => 'Invalid login credentials.']);
});

// --- GOOGLE OAUTH REDIRECTS ---
Route::get('/login/google', function () {
    $query = http_build_query([
        'client_id'     => env('GOOGLE_CLIENT_ID'),
        'redirect_uri'  => env('GOOGLE_REDIRECT_URI'),
        'response_type' => 'code',
        'scope'         => 'openid email profile',
        'access_type'   => 'online',
        'prompt'        => 'select_account'
    ]);

    return redirect('https://accounts.google.com/o/oauth2/v2/auth?' . $query);
});

Route::get('/login/google/callback', function (Request $request) {
    if (!$request->has('code')) {
        return redirect('/login')->withErrors(['email' => 'Google Authentication access denied.']);
    }

    try {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://oauth2.googleapis.com/token');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'code'          => $request->query('code'),
            'client_id'     => env('GOOGLE_CLIENT_ID'),
            'client_secret' => env('GOOGLE_CLIENT_SECRET'),
            'redirect_uri'  => env('GOOGLE_REDIRECT_URI'),
            'grant_type'    => 'authorization_code'
        ]));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $tokenResponse = json_decode(curl_exec($ch), true);
        curl_close($ch);

        if (!isset($tokenResponse['access_token'])) {
            return redirect('/login')->withErrors(['email' => 'Failed to obtain access token from Google.']);
        }

        $profileCh = curl_init();
        curl_setopt($profileCh, CURLOPT_URL, 'https://www.googleapis.com/oauth2/v3/userinfo?access_token=' . $tokenResponse['access_token']);
        curl_setopt($profileCh, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($profileCh, CURLOPT_SSL_VERIFYPEER, false);

        $googleUser = json_decode(curl_exec($profileCh), true);
        curl_close($profileCh);

        if (!isset($googleUser['email'])) {
            return redirect('/login')->withErrors(['email' => 'Unable to read profile parameters from Google account.']);
        }

        $email = $googleUser['email'];

        $firestore = getFirestore();
        $userDoc = $firestore->collection('users')->document($email)->snapshot();

        if (!$userDoc->exists()) {
            return redirect('/login')->withErrors([
                'email' => 'No account found with this Google address. Please sign up normally first!'
            ]);
        }

        session([
            'user_email' => $email,
            'user_name'  => $userDoc->get('name')
        ]);

        return redirect('/dashboard?tab=home');

    } catch (\Exception $e) {
        return redirect('/login')->withErrors(['email' => 'Internal Handshake Error: ' . $e->getMessage()]);
    }
});

// --- REGISTER & OTP VERIFICATION TRACKING (MANUAL SIGN UP) ---
Route::get('/register', function () {
    if (session()->has('user_email')) return redirect('/dashboard');
    return view('auth.register');
});

Route::post('/register', function (Request $request) {
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255'],
        'password' => ['required', 'string', 'min:6'],
    ]);

    $firestore = getFirestore();
    $existingUser = $firestore->collection('users')->document($request->email)->snapshot();
    if ($existingUser->exists()) {
        return back()->withErrors(['email' => 'This email address is already registered.']);
    }

    $otpCode = rand(100000, 999999);

    session([
        'registration_data' => [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ],
        'registration_otp' => $otpCode,
        'otp_expires_at' => now()->addMinutes(15)
    ]);

    try {
        Mail::raw("Your Furecipe security code verification pin token is: {$otpCode}. This expires within 15 minutes.", function ($message) use ($request) {
            $message->to($request->email)->subject('Verify Your Furecipe Platform Registration 🐾');
        });
    } catch (\Exception $e) {
        // Safe fallback
    }

    return redirect('/register/verify-otp');
});

Route::get('/register/verify-otp', function() {
    if (!session()->has('registration_data')) return redirect('/register');
    return view('auth.verify-otp');
});

Route::post('/register/verify-otp', function(Request $request) {
    $request->validate(['otp' => 'required|string|size:6']);

    if (!session()->has('registration_data') || now()->gt(session('otp_expires_at'))) {
        session()->forget(['registration_data', 'registration_otp', 'otp_expires_at']);
        return redirect('/register')->withErrors(['email' => 'Your validation window expired. Please try again.']);
    }

    if ($request->otp !== (string)session('registration_otp')) {
        return back()->with('error', 'The security code you entered is invalid.');
    }

    $userData = session('registration_data');
    $firestore = getFirestore();
    $firestore->collection('users')->document($userData['email'])->set([
        'name' => $userData['name'],
        'email' => $userData['email'],
        'password' => $userData['password'],
        'email_verified_at' => now()->toIso8601String()
    ]);

    session(['user_email' => $userData['email'], 'user_name' => $userData['name']]);
    session()->forget(['registration_data', 'registration_otp', 'otp_expires_at']);

    return redirect('/dashboard?tab=home');
});

// --- LOGOUT ROUTE ---
Route::get('/logout', function () {
    session()->forget(['user_email', 'user_name', 'cart']);
    return redirect('/');
});

// =========================================================================
// 3. CORE SECURE APPLICATION WORKSPACE
// =========================================================================
Route::get('/dashboard', function (Request $request) {
    if (!session()->has('user_email')) return redirect('/login');

    $email = session('user_email');
    $products = [];
    $pets = [];
    $orders = [];

    try {
        $firestore = getFirestore();

        // Load Products
        $productDocs = $firestore->collection('products')->documents();
        foreach ($productDocs as $doc) {
            if ($doc->exists()) {
                $data = $doc->data();
                $data['id'] = $doc->id();
                $products[] = $data;
            }
        }

        // Load Recipes
        $recipeDocs = $firestore->collection('pet_calculations')->where('owner_email', '=', $email)->documents();
        foreach ($recipeDocs as $doc) {
            if ($doc->exists()) {
                $data = $doc->data();
                $data['id'] = $doc->id();
                $data['document_id'] = $doc->id();
                $pets[] = $data;
            }
        }

        // Load Orders
        $orderDocs = $firestore->collection('orders')->where('buyer_email', '=', $email)->documents();
        foreach ($orderDocs as $doc) {
            if ($doc->exists()) {
                $data = $doc->data();
                $data['id'] = $doc->id();
                $orders[] = $data;
            }
        }
    } catch (\Exception $e) {
        // Safe fallback
    }

    $blogs = [
        [
            'title' => 'The Risk of Homemade Diets: Are You Missing Critical Minerals?',
            'excerpt' => 'Feeding a real-food home-cooked diet without a verified balancing premix agent can slowly cause serious calcium-to-phosphorus ratio imbalances over time. Learn how to safeguard organ health.',
            'date' => 'May 16, 2026'
        ],
        [
            'title' => 'Managing Dog Allergies with Clean Single-Source Proteins',
            'excerpt' => 'Is your pet dealing with chronic red, itchy skin rashes or hot spots? Eliminating ultra-processed commercial kibble fillers and switching to a targeted, 12-week limited ingredient protocol resets immune pathways.',
            'date' => 'May 11, 2026'
        ],
        [
            'title' => 'Feline Taurine Requirements: A Non-Negotiable Reality',
            'excerpt' => 'Unlike canines, cats cannot synthesize taurine internally, and cooking alters meat moisture profiles where nutrients are lost. Precise blending with organ configurations keeps cardiac functions safe.',
            'date' => 'May 03, 2026'
        ]
    ];

    $weeklyRecipes = [
        'dog' => [
            'Monday'    => ['name' => 'Date Night Turkey & Macaroni', 'ingredients' => '3 lbs 85% lean ground turkey, 2 Tbsp coconut oil, 1 lb carrots, 8 oz broccoli, 2 cups macaroni, 2 tsp HVB Omega-3 Fish Oil, 2 Tbsp HVB Canine Limited Premix', 'calories' => '255 kcal/cup'],
            'Tuesday'   => ['name' => 'Turkey, Rolled Oats & Veggies', 'ingredients' => '3 lbs ground turkey, 2 Tbsp coconut oil, 4 oz kale, 1 lb carrots, 2.5 cups rolled oats, 2 tsp HVB Fish Oil, 2 Tbsp HVB Canine Limited Premix', 'calories' => '255 kcal/cup'],
            'Wednesday' => ['name' => 'Everyone\'s Favorite Chicken Thighs', 'ingredients' => '3 lbs boneless chicken thighs, brown rice, carrots, broccoli, 2 tsp HVB Fish Oil, 2 Tbsp HVB Regular Premix', 'calories' => '260 kcal/cup'],
            'Thursday'  => ['name' => 'Beef & Macaroni Comfort Bowl', 'ingredients' => '3 lbs ground beef, gluten-free macaroni, diced carrots, chopped kale, 2 tsp HVB Fish Oil, 2 Tbsp HVB Regular Premix', 'calories' => '270 kcal/cup'],
            'Friday'    => ['name' => 'Tilapia & Sweet Potato Vitality', 'ingredients' => '3 lbs tilapia fillets, baked sweet potato, carrots, asparagus tips, 2 tsp HVB Fish Oil, 2 Tbsp HVB Limited Premix', 'calories' => '240 kcal/cup'],
            'Saturday'  => ['name' => 'Turkey, Quinoa & Super Greens', 'ingredients' => '3 lbs ground turkey, organic quinoa, diced carrots, finely chopped curly kale, 2 tsp HVB Fish Oil, 2 Tbsp HVB Limited Premix', 'calories' => '250 kcal/cup'],
            'Sunday'    => ['name' => 'Beef, Gizzards & Brown Rice Feast', 'ingredients' => '2.5 lbs ground beef, 0.5 lbs chicken gizzards, brown rice, fresh kale, carrots, 2 tsp HVB Fish Oil, 2 Tbsp HVB Regular Premix', 'calories' => '280 kcal/cup'],
        ],
        'cat' => [
            'Monday'    => ['name' => 'Violet\'s Chicken Dinner', 'ingredients' => '3 lbs chicken thighs (skinless/boneless), 2 cups water, 2 tsp HVB Omega-3 Fish Oil, 4.5 tsp HVB Feline Premix', 'calories' => '35 kcal/oz'],
            'Tuesday'   => ['name' => 'Chicken and Sardine Special', 'ingredients' => '2.5 lbs chicken thighs, 1 can sardines in water (no salt), 2 cups water, 2 tsp HVB Fish Oil, 4.5 tsp HVB Feline Premix', 'calories' => '38 kcal/oz'],
            'Wednesday' => ['name' => 'Turkey and Sardine Pâté', 'ingredients' => '2.5 lbs ground turkey, 1 can sardines, minced parsley, 2 cups broth, 2 tsp HVB Fish Oil, 4.5 tsp HVB Feline Premix', 'calories' => '36 kcal/oz'],
            'Thursday'  => ['name' => 'Violet\'s Turkey Florentine', 'ingredients' => '3 lbs ground turkey, 1/2 cup chopped baby spinach, 2 cups water, 2 tsp HVB Fish Oil, 4.5 tsp HVB Feline Premix', 'calories' => '34 kcal/oz'],
            'Friday'    => ['name' => 'Turkey & Wild Salmon Medley', 'ingredients' => '2 lbs ground turkey, 1 lb wild salmon fillet, 1 hardboiled egg, 2 cups water, 2 tsp HVB Fish Oil, 4.5 tsp HVB Feline Premix', 'calories' => '40 kcal/oz'],
            'Saturday'  => ['name' => 'Chicken with Mushrooms & Egg', 'ingredients' => '3 lbs chicken thighs, 1/4 cup button mushrooms (cooked), 1 hardboiled egg, 2 tsp HVB Fish Oil, 4.5 tsp HVB Feline Premix', 'calories' => '37 kcal/oz'],
            'Sunday'    => ['name' => 'Turkey a La Catnip Treatment', 'ingredients' => '3 lbs ground turkey, 1 tsp organic catnip flakes, 2 cups water, 2 tsp HVB Fish Oil, 4.5 tsp HVB Feline Senior Premix', 'calories' => '33 kcal/oz'],
        ]
    ];

    $activeTab = $request->query('tab', 'home');
    return view('dashboard', compact('products', 'pets', 'orders', 'blogs', 'activeTab', 'weeklyRecipes'));
});

// ==========================================
// 4. DIETARY FORMULATION CALCULATOR
// ==========================================
Route::post('/calculator/compute', [PetController::class, 'calculate']);
Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

// ==========================================
// 5. TRANSACTIONAL CART INTERACTION ENGINE
// ==========================================
Route::post('/cart/add', [CartController::class, 'add']);
Route::post('/cart/update', [CartController::class, 'update']);
Route::post('/cart/remove', [CartController::class, 'remove']);
Route::post('/cart/clear', [CartController::class, 'clear']);

// --- CHECKOUT SUB-SYSTEM ---
Route::get('/checkout', function() {
    if (!session()->has('user_email')) return redirect('/login');
    return view('checkout');
});

Route::post('/checkout', function(Request $request) {
    $cart = session()->get('cart', []);
    if (empty($cart)) return redirect('/dashboard?tab=shop');

    $total = 0;
    foreach($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }

    $firestore = getFirestore();
    $firestore->collection('orders')->add([
        'buyer_email' => session('user_email'),
        'shipping_details' => [
            'name' => $request->input('shipping_name'),
            'phone' => $request->input('shipping_phone'),
            'address' => $request->input('shipping_address'),
            'payment_mode' => $request->input('payment_method', 'COD')
        ],
        'items' => array_values($cart),
        'grand_total' => $total,
        'order_status' => 'Preparing Shipment',
        'timestamp' => now()->toIso8601String()
    ]);

    session()->forget('cart');
    return redirect('/dashboard?tab=me');
});

Route::post('/place-order', [CartController::class, 'placeOrder'])->name('order.place');
Route::get('/paymongo/callback', [CartController::class, 'handlePayMongoCallback']);

// ==========================================
// 6. INVENTORY SEEDER
// ==========================================
Route::get('/seed-shop', function() {
    $firestore = getFirestore();
    $existing = $firestore->collection('products')->documents();
    foreach($existing as $doc) {
        if($doc->exists()) { $doc->reference()->delete(); }
    }

    $products = [
        ['name' => 'Holistic Vet Blend Feline Premix', 'price' => 1900, 'image' => '/images/photo12.png'],
        ['name' => 'Canine Limited Premix', 'price' => 1900, 'image' => '/images/photo11.png'],
        ['name' => 'Feline Mobility Plus', 'price' => 2100, 'image' => '/images/photo10.png'],
        ['name' => 'Icelandic Blend Omega-3 Fish Oil', 'price' => 1800, 'image' => '/images/photo9.png'],
        ['name' => 'Green Omega-3', 'price' => 1800, 'image' => '/images/photo8.png'],
        ['name' => 'Canine Regular Premix with Grass-fed Beef Liver', 'price' => 2100, 'image' => '/images/photo7.png'],
        ['name' => 'Feline Senior Premix', 'price' => 2100, 'image' => '/images/photo6.png'],
        ['name' => 'Adult Cat Essentials Starter Pack', 'price' => 3500, 'image' => '/images/photo5.png'],
        ['name' => 'Allergy-Safe Dog Food Kit - Sensitive & Limited Diet Support', 'price' => 5400, 'image' => '/images/photo4.png'],
        ['name' => 'Fresh Start Bundle - For New Dog Parents', 'price' => 3800, 'image' => '/images/photo3.png'],
        ['name' => 'Multi-Pet Starter Set', 'price' => 5700, 'image' => '/images/photo2.png'],
        ['name' => 'Senior Cat Wellness Pack, Mobility, Kidney, & Omega Support', 'price' => 5700, 'image' => '/images/photo1.png']
    ];

    foreach ($products as $prod) {
        $firestore->collection('products')->add($prod);
    }
    return "Success! 12 distinct product items seeded.";
});

// =========================================================================
// 7. PET MEAL PLAN LIFECYCLE MANAGEMENT ENGINE
// =========================================================================
Route::delete('/pets/{id}', function ($id) {
    if (!session()->has('user_email')) return redirect('/login');

    try {
        $firestore = getFirestore();
        $email = session('user_email');

        $docRef = $firestore->collection('pet_calculations')->document($id);
        $snapshot = $docRef->snapshot();

        if ($snapshot->exists() && $snapshot->get('owner_email') === $email) {
            $docRef->delete();
            return redirect('/dashboard?tab=plan')->with('success', 'Feeding profile plan has been removed successfully.');
        }

        return redirect('/dashboard?tab=plan')->with('error', 'Unauthorized action or plan missing.');
    } catch (\Exception $e) {
        return redirect('/dashboard?tab=plan')->with('error', 'Failed to remove plan: ' . $e->getMessage());
    }
});
