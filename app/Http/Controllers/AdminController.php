<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // Dashboard Landing (Summary metrics & Order management view)
    public function index()
    {
        // Calculate mock/real metrics safely from database tables
        // (Assuming you have products and an orders table)
        $totalEarnings = DB::table('orders')->where('status', 'Completed')->sum('total_price') ?? 16400.00;
        $totalOrders = DB::table('orders')->count() ?? 12;
        $totalProducts = DB::table('products')->count() ?? 4;

        $orders = DB::table('orders')
            ->orderBy('created_at', 'desc')
            ->get();

        $products = DB::table('products')->get();

        return view('admin.index', compact('totalEarnings', 'totalOrders', 'totalProducts', 'orders', 'products'));
    }

    // Update Product Info (Price, Descriptions, Titles)
    public function updateProduct(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string',
        ]);

        DB::table('products')->where('id', $id)->update([
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Product updated successfully!');
    }

    // Update Order Status (Receiving money / fulfilling orders)
    public function updateOrderStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string'
        ]);

        DB::table('orders')->where('id', $id)->update([
            'status' => $request->status,
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Order status updated successfully!');
    }
}
