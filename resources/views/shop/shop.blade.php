@extends('layouts/app')

@section('title', 'Shop')
@section('content')
    <section class="shop-container">
        <div class="shop-header">
            <h1>Our Products</h1>

            <form method="GET" action="{{ route('shop.index') }}" class="category-filter">
                <select name="category" onchange="this.form.submit()">
                    <option value="">All Categories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ $selectedCategory == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="products-grid">
            @forelse ($products as $product)
                <div class="product-card">
                    <a href="{{ route('products.show', $product) }}">
                        @if ($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                class="product-image">
                        @else
                            <div class="product-image-placeholder">No Image</div>
                        @endif
                        <h4>{{ $product->name }}</h4>
                        <p class="price">${{ number_format($product->price, 2) }}</p>
                        @if (auth()->check() && auth()->user()->isItCommercial())
                            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-warning">Modify</a>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        @endif
                        @if ($product->reviewCount() > 0)
                            <div class="product-rating">
                                @php $avgRating = round($product->averageRating()) @endphp
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $avgRating)
                                        ★
                                    @else
                                        ☆
                                    @endif
                                @endfor
                                <span class="rating-count">({{ $product->reviewCount() }})</span>
                            </div>
                        @else
                            <div class="no-rating">No ratings yet</div>
                        @endif
                    </a>
                </div>
            @empty
                <div class="no-products">
                    <p>No products found in this category.</p>
                </div>
            @endforelse
        </div>

        <div class="pagination">
            {{ $products->links() }}
        </div>
    </section>

    <style>
        /* Product Rating in Shop */
        .product-rating {
            color: #ffc107;
            margin: 0.5rem 0;
            font-size: 0.9rem;
        }

        .rating-count {
            color: #777;
            font-size: 0.8rem;
        }

        .no-rating {
            color: #ccc;
            font-size: 0.9rem;
            margin: 0.5rem 0;
            font-style: italic;
        }
    </style>
@endsection
