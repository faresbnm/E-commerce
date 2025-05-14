<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

// app/Http/Controllers/ReviewController.php
public function store(Request $request, Product $product)
{
    $request->validate([
        'rating' => 'required|integer|between:1,5',
        'comment' => 'nullable|string|max:500'
    ]);

    $review = new Review([
        'rating' => $request->rating,
        'comment' => $request->comment,
        'user_id' => auth()->id()
    ]);

    $product->reviews()->save($review);

    return back()->with('success', 'Review submitted successfully');
}

    public function update(Request $request, Review $review)
    {
        $this->authorize('update', $review);

        $validated = $request->validate([
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:1000'
        ]);

        $review->update($validated);

        return redirect()->back()->with('success', 'Review updated successfully');
    }

    public function destroy(Review $review)
    {
        $this->authorize('delete', $review);
        $review->delete();
        return redirect()->back()->with('success', 'Review deleted successfully');
    }
}