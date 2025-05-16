<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\GalleryImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index()
    {
        $galleries = Gallery::approved()
            ->with(['images' => function($query) {
                $query->orderBy('order');
            }])
            ->latest()
            ->paginate(12);

        return view('gallery.index', compact('galleries'));
    }

    public function create()
    {
        return view('gallery.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'images' => 'required|array|min:1',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $gallery = Gallery::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'status' => 'pending'
        ]);

        foreach ($request->file('images') as $key => $image) {
            $path = $image->store('gallery', 'public');
            
            GalleryImage::create([
                'gallery_id' => $gallery->id,
                'image_path' => $path,
                'order' => $key
            ]);
        }

        return redirect()->route('shop.index')
            ->with('success', 'Gallery submitted for approval');
    }

    public function show(Gallery $gallery)
    {
        $gallery->load('images'); // Eager load the images relationship
        return view('gallery.show', compact('gallery'));
    }

    public function destroy(Gallery $gallery)
    {
        if (auth()->user()->isItCommercial() || $gallery->user_id == auth()->id()) {
            foreach ($gallery->images as $image) {
                Storage::delete('public/' . $image->image_path);
                $image->delete();
            }
            $gallery->delete();
            return back()->with('success', 'Gallery deleted');
        }

        abort(403);
    }
}