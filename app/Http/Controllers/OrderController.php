<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmed;



class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $orders = Auth::user()->orders()->with('items.product')->latest()->paginate(10);
        return view('orders.orders', compact('orders'));
    }

    public function cancel(Order $order)
    {
    // Verify the order belongs to the current user
    if ($order->user_id !== auth()->id()) {
        abort(403);
    }

    // Only allow cancellation if order is pending
    if ($order->status !== 'pending') {
        return back()->with('error', 'Order can only be cancelled if still pending');
    }

    $order->update(['status' => 'cancelled']);
    
    return back()->with('success', 'Order cancelled successfully');
}

public function store(Request $request)
{
    if (!auth()->check()) {
        return response()->json([
            'success' => false,
            'message' => 'You must be logged in to place an order'
        ], 401);
    }
    
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'quantity' => 'required|integer|min:1'
    ]);
    
    try {
        $product = Product::findOrFail($request->product_id);
        
        // Create the order
        $order = Order::create([
            'user_id' => auth()->id(),
            'status' => 'pending',
            'total' => $product->price * $request->quantity
        ]);
        
        // Add order item
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => $request->quantity,
            'price' => $product->price,
            'total' => $product->price * $request->quantity
        ]);
        
        try {
            Mail::to(auth()->user()->email)->send(new OrderConfirmed($order));
        } catch (\Exception $e) {
            // Log email error but don't fail the order
            \Log::error('Failed to send order confirmation email: '.$e->getMessage());
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Order placed successfully',
            'order_id' => $order->id
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error placing order: ' . $e->getMessage()
        ], 500);
    }
}

    public function show(Order $order)
    {
        $this->authorize('view', $order);
        return view('orders.orders', compact('order'));
    }

    public function storeFromCart(Request $request)
    {
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }
    
        // Create the order
        $order = Order::create([
            'user_id' => auth()->id(),
            'status' => 'pending',
            'total' => $this->calculateCartTotal($cart),
        ]);
    
        // Add all cart items as order items
        foreach ($cart as $productId => $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $productId,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'total' => $item['price'] * $item['quantity']
            ]);
        }
    
        try {
            Mail::to(auth()->user()->email)->send(new OrderConfirmed($order));
        } catch (\Exception $e) {
            \Log::error('Failed to send order confirmation email: '.$e->getMessage());
        }
    
        // Clear the cart
        session()->forget('cart');
    
        return redirect()->route('home', $order)
               ->with('success', 'Order placed successfully!');
    }

    protected function calculateCartTotal($cart)
    {
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }
}