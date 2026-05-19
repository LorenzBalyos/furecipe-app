<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Cloud\Firestore\FirestoreClient;
use Illuminate\Support\Facades\Http;

class CartController extends Controller
{
    /**
     * Direct initialization to your Google NoSQL Firestore pipeline
     */
    private function getFirestoreInstance() {
        $path = storage_path('firebase_credentials.json');
        if (!file_exists($path)) {
            $path = storage_path('app/firebase_credentials.json');
        }
        putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $path);
        return new FirestoreClient(['transport' => 'rest']);
    }

    public function add(Request $request)
    {
        $cart = session()->get('cart', []);
        $id = $request->product_id;

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += $request->quantity;
        } else {
            $cart[$id] = [
                "name" => $request->name,
                "quantity" => $request->quantity,
                "price" => $request->price,
                "image" => $request->image
            ];
        }

        session()->put('cart', $cart);
        return response()->json(['success' => true, 'count' => count($cart)]);
    }

    public function update(Request $request)
    {
        $cart = session()->get('cart', []);
        $id = $request->product_id;
        $delta = (int)$request->delta;

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += $delta;
            if ($cart[$id]['quantity'] <= 0) {
                unset($cart[$id]);
            }
            session()->put('cart', $cart);
        }

        return response()->json(['success' => true]);
    }

    public function remove(Request $request)
    {
        $cart = session()->get('cart', []);
        $id = $request->product_id;

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return response()->json(['success' => true]);
    }

    public function clear()
    {
        session()->forget('cart');
        return back();
    }

    public function placeOrder(Request $request)
    {
        // 📋 Strict matching rules validation
        $request->validate([
            'shipping_email'      => 'required|email|max:255',
            'shipping_first_name' => 'required|string|max:255',
            'shipping_last_name'  => 'required|string|max:255',
            'shipping_address'    => 'required|string',
            'shipping_city'       => 'required|string|max:255',
            'shipping_region'     => 'required|string|max:255',
            'shipping_postal'     => 'required|string|max:10',
            'payment_method'      => 'required|string',
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Basket is empty!'], 400);
            }
            return redirect()->back()->with('error', 'Basket is empty!');
        }

        $total = 0;
        foreach($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        try {
            $paymentMode = $request->payment_method;
            $orderStatus = ($paymentMode === 'COD') ? 'Preparing Shipment' : 'Payment Verified Successfully';

            // --- 💳 PAYMONGO REAL-TIME LINK INTEGRATION GATEWAY ---
            if ($paymentMode !== 'COD') {
                $secretKey = env('PAYMONGO_SECRET_KEY');

                // Map select inputs to official PayMongo payment method type values
                $paymongoMethodType = 'gcash';
                if ($paymentMode === 'Maya') {
                    $paymongoMethodType = 'paymaya';
                } elseif ($paymentMode === 'CreditCard') {
                    $paymongoMethodType = 'card';
                }

                // Call PayMongo API to generate a checkout session link
                $response = Http::withHeaders([
                    'Authorization' => 'Basic ' . base64_encode($secretKey . ':'),
                    'Content-Type'  => 'application/json',
                ])->post('https://api.paymongo.com/v1/checkout_sessions', [
                    'data' => [
                        'attributes' => [
                            'amount'           => intval($total * 100), // Convert total pesos into centavos
                            'payment_method_types' => [$paymongoMethodType],
                            'currency'         => 'PHP',
                            'description'      => 'Furecipe Pet Store Order Checkout Authorization',
                            'line_items' => array_map(function($item) {
                                return [
                                    'amount'   => intval($item['price'] * 100),
                                    'currency' => 'PHP',
                                    'name'     => $item['name'],
                                    'quantity' => intval($item['quantity'])
                                ];
                            }, array_values($cart)),
                            'success_url'      => url('/dashboard?tab=me'),
                            'cancel_url'       => url('/checkout'),
                        ]
                    ]
                ]);

                if ($response->failed()) {
                    $errorMsg = $response->json()['errors'][0]['detail'] ?? 'PayMongo failed to initialize checkout token.';
                    if ($request->ajax()) {
                        return response()->json(['success' => false, 'error' => $errorMsg], 400);
                    }
                    return redirect()->back()->with('error', $errorMsg);
                }

                // Extract live/sandbox redirect URL provided by PayMongo server
                $paymongoCheckoutUrl = $response->json()['data']['attributes']['checkout_url'] ?? null;
            }

            // --- 🗄️ SAVE PERMANENT ORDER LOGS DIRECTLY TO FIRESTORE NO SQL ---
            $firestore = $this->getFirestoreInstance();
            $newOrderRef = $firestore->collection('orders')->newDocument();
            $orderId = $newOrderRef->id();

            $newOrderRef->set([
                'id'          => $orderId,
                'buyer_email' => session('user_email', $request->shipping_email),
                'buyer_name'  => $request->shipping_first_name . ' ' . $request->shipping_last_name,
                'shipping_details' => [
                    'address'      => $request->shipping_address,
                    'city'         => $request->shipping_city,
                    'region'       => $request->shipping_region,
                    'postal_code'  => $request->shipping_postal
                ],
                'items'        => array_values($cart),
                'grand_total'  => $total,
                'order_status' => $orderStatus,
                'payment_mode' => $paymentMode,
                'timestamp'    => now()->toIso8601String()
            ]);

            // Clear operational shopping basket cache
            session()->forget('cart');
            session()->flash('success', 'Purchase completed! Order recorded in your profile.');

            // Determine redirect target destination
            $finalRedirectTarget = ($paymentMode !== 'COD' && !empty($paymongoCheckoutUrl))
                ? $paymongoCheckoutUrl
                : '/dashboard?tab=me';

            if ($request->ajax()) {
                return response()->json(['success' => true, 'redirect' => $finalRedirectTarget]);
            }

            return redirect($finalRedirectTarget);

        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
