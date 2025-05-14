<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        
        // Calculate subtotal
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        
        return view('cart.index', compact('cart', 'subtotal'));
    }

    public function add(Product $product, Request $request)
    {
        try {
            $request->validate([
                'quantity' => 'required|integer|min:1'
            ]);
    
            $cart = session()->get('cart', []);
            $quantity = $request->quantity;
    
            if (isset($cart[$product->id])) {
                $cart[$product->id]['quantity'] += $quantity;
            } else {
                $cart[$product->id] = [
                    "name" => $product->name,
                    "quantity" => $quantity,
                    "price" => $product->price,
                    "image" => $product->image ?? null
                ];
            }
    
            session()->put('cart', $cart);
    
            return response()->json([
                'success' => true,
                'cart_count' => count($cart),
                'message' => 'Product added to cart'
            ]);
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);
        
        $cart = session()->get('cart', []);
        
        if (isset($cart[$id])) {
            if ($request->quantity <= 0) {
                unset($cart[$id]);
                session()->put('cart', $cart);
                return redirect()->route('cart.index')->with('success', 'Product removed from cart');
            } else {
                $cart[$id]['quantity'] = $request->quantity;
                session()->put('cart', $cart);
                return redirect()->route('cart.index')->with('success', 'Cart updated');
            }
        }
        
        return redirect()->route('cart.index')->with('error', 'Product not found in cart');
    }

    public function remove($id)
    {
        $cart = session()->get('cart', []);
        
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Product removed from cart');
    }

}