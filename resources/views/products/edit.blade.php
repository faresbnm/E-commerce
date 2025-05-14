@extends('layouts.app')

@section('title', 'Create Product')
@section('content')
    <div class="product-form-container">
        <h1>{{ isset($product) ? 'Edit' : 'Create' }} Product</h1>

        <form action="{{ isset($product) ? route('products.update', $product) : route('products.store') }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @if (isset($product))
                @method('PUT')
            @endif

            <div class="form-group">
                <label for="name">Product Name</label>
                <input type="text" id="name" name="name" value="{{ old('name', $product->name ?? '') }}" required
                    class="form-control">
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" required class="form-control" rows="3">{{ old('description', $product->description ?? '') }}</textarea>
            </div>

            <div class="form-group">
                <label for="price">Price</label>
                <input type="number" id="price" name="price" step="0.01" min="0"
                    value="{{ old('price', $product->price ?? '') }}" required class="form-control">
            </div>

            <div class="form-group">
                <label for="stock">Stock</label>
                <input type="number" id="stock" name="stock" min="0"
                    value="{{ old('stock', $product->stock ?? '') }}" required class="form-control">
            </div>

            <div class="form-group">
                <label for="image">Product Image</label>
                <input type="file" id="image" name="image" {{ !isset($product) ? 'required' : '' }}
                    class="form-control">
                @if (isset($product) && $product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="Current Image" class="current-image">
                @endif
            </div>

            <button type="submit" class="btn btn-primary">{{ isset($product) ? 'Update' : 'Create' }} Product</button>
        </form>
    </div>

    <style>
        .product-form-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .current-image {
            max-width: 200px;
            margin-top: 1rem;
        }
    </style>
@endsection
