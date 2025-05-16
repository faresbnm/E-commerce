<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\Gallery;
use App\Models\User;
use Illuminate\Support\Facades\Storage;



class AdminController extends Controller
{
    // app/Http/Controllers/AdminController.php
    public function dashboard()
    {
        $stats = [
            'total_sales' => Order::sum('total'),
            'total_orders' => Order::count(),
            'pending_products' => Product::where('status', 'pending')->count(),
            'pending_galleries' => Gallery::where('status', 'pending')->count()
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

public function pendingGalleries()
{
    $galleries = Gallery::with(['user', 'images'])
                ->where('status', 'pending')
                ->latest()
                ->paginate(10);
                
    return view('admin.pendingGallery', compact('galleries'));
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

public function approveGallery(Gallery $gallery)
{
    $gallery->update(['status' => 'approved']);
    return back()->with('success', 'Gallery approved');
}

public function rejectGallery(Gallery $gallery)
{
    foreach ($gallery->images as $image) {
        Storage::delete('public/' . $image->image_path);
        $image->delete();
    }
    $gallery->delete();
    return back()->with('success', 'Gallery rejected');
}
}
