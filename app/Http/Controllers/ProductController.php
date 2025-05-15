<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Add this line


class ProductController extends Controller
{
    public function index()
    {
        $categories = Category::with(['products' => function($query) {
            $query->where('status', 'approved')
                ->latest()
                ->take(4); // 4 products per category
        }])->get();
    
        return view('home', compact('categories'));
    }

    public function show(Product $product)
    {
        // Only show approved products or allow admin to see pending ones
        if ($product->status !== 'approved' && !auth()->user()?->isAdmin()) {
            abort(404);
        }
        
        $userReview = null;
        $userHasPurchased = false;
        
        if (auth()->check()) {
            $userReview = $product->reviews()->where('user_id', auth()->id())->first();
            $userHasPurchased = Order::where('user_id', auth()->id())
                ->whereHas('items', function($query) use ($product) {
                    $query->where('product_id', $product->id);
                })->exists();
        }
        
        return view('shop.productDetail', compact('product', 'userReview', 'userHasPurchased'));
    }

    public function shop()
    {
        $categories = Category::all();
        $selectedCategory = request('category');
        
        $products = Product::where('status', 'approved')->when($selectedCategory, function($query) use ($selectedCategory) {
                return $query->whereHas('categories', function($q) use ($selectedCategory) {
                    $q->where('id', $selectedCategory);
                });
            })
            ->with('categories')
            ->paginate(12);
        
        return view('shop.shop', compact('categories', 'products', 'selectedCategory'));
    }

    // app/Http/Controllers/ProductController.php
public function create()
{
    $categories = Category::get();
    return view('products.create', compact('categories'));
}

public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'price' => 'required|numeric|min:0',
        'stock' => 'required|integer|min:0',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        'categories' => 'required|array',
        'categories.*' => 'exists:categories,id',
    ]);

    // Ensure user is authenticated
    if (!auth()->check()) {
        return redirect()->back()->with('error', 'You must be logged in to create a product');
    }

    $imagePath = $request->file('image')->store('products', 'public');

    $product = Product::create([
        'name' => $request->name,
        'description' => $request->description,
        'price' => $request->price,
        'stock' => $request->stock,
        'image' => $imagePath,
        'status' => 'pending',
        'user_id' => auth()->id() // Make sure this is set
    ]);

    $product->categories()->attach($request->categories);

    return redirect()->route('shop.index')
        ->with('success', 'Product submitted for approval. An admin will review it shortly.');
}

public function update(Request $request, Product $product)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'price' => 'required|numeric|min:0',
        'stock' => 'required|integer|min:0',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'categories' => 'required|array',
        'categories.*' => 'exists:categories,id'
    ]);

    $data = $request->only(['name', 'description', 'price', 'stock']);

    if ($request->hasFile('image')) {
        // Delete old image
        Storage::delete('public/' . $product->image);
        
        // Store new image
        $imagePath = $request->file('image')->store('public/products');
        $data['image'] = str_replace('public/', '', $imagePath);
    }

    $product->update($data);
    $product->categories()->sync($request->categories);

    return redirect()->route('products.show', $product)->with('success', 'Product updated successfully');
}

public function destroy(Product $product)
{
    // Delete the image file
    Storage::delete('public/' . $product->image);
    $product->delete();
    return redirect()->route('shop.index')->with('success', 'Product deleted successfully');
}

public function edit(Product $product)
{
    $categories = Category::all();
    return view('products.edit', compact('product', 'categories'));
}

}