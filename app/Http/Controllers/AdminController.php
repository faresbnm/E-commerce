<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\Gallery;
use App\Models\User;


class AdminController extends Controller
{
    // app/Http/Controllers/AdminController.php
public function dashboard()
{
    $stats = [
        'total_sales' => Order::sum('total'),
        'total_orders' => Order::count(),
        'pending_approvals' => Gallery::where('approved', false)->count()
    ];
    
    return view('admin.dashboard', compact('stats'));
}

public function userManagement()
{
    $users = User::where('role', '!=', 'admin')->get();
    return view('admin.users', compact('users'));
}

public function deleteUser(User $user)
{
    $user->delete();
    return back()->with('success', 'User deleted successfully');
}

public function pendingProducts()
{
    $products = Product::where('status', 'pending')->get();
    return view('admin.pending', compact('products'));
}

public function approveProduct(Product $product)
{
    $product->update(['status' => 'approved']);
    return back()->with('success', 'Product approved successfully');
}

public function rejectProduct(Product $product)
{
    Storage::delete('public/' . $product->image);
    $product->delete();
    return back()->with('success', 'Product rejected and removed');
}
}
