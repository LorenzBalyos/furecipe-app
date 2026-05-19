<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; // Or your custom array/DB model

class DashboardController extends Controller
{
    public function index()
    {
        // Fetching products out of the route file and passing them neatly to the views
        $products = Product::all()->toArray();

        return view('dashboard', compact('products'));
    }

    public function seedShop()
    {
        // If you have a quick routine to populate your database matrix:
        // Product::create([...]);
        return back()->with('success', 'Shop products seeded perfectly!');
    }
}
