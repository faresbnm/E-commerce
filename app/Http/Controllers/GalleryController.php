<?php

namespace App\Http\Controllers;

use App\Models\Gallery;

class GalleryController extends Controller
{
// app/Http/Controllers/GalleryController.php
public function create()
{
    return view('gallery.create');
}

public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'image' => 'required|image|max:2048'
    ]);

    $imagePath = $request->file('image')->store('gallery', 'public');

    Gallery::create([
        'user_id' => auth()->id(),
        'title' => $request->title,
        'description' => $request->description,
        'image' => $imagePath
    ]);

    return redirect()->route('gallery.index')->with('success', 'Gallery item submitted for approval');
}
}