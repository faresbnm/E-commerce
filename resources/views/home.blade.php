@extends('layouts/app')

@section('title', 'Home')
@section('content')
    <section class="hero">
        <h1>
            @auth
                Welcome {{ Auth::User()->nom }} {{ Auth::User()->prenom }} to Our Store
            @else
                Welcome to our store
            @endauth
        </h1>
        <p>Discover the best products of the month</p>
    </section>

    <section class="featured-products">
        <h2>Best Sellers This Month</h2>

        <div class="categories">
            @foreach ($categories as $category)
                <div class="category-section">
                    <h3>{{ $category->name }}</h3>

                    <div class="products-grid">
                        @foreach ($category->products()->orderBy('sales', 'desc')->take(3)->get() as $product)
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
                                <form action="{{ route('cart.add', $product) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="add-to-cart">Add to Cart</button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
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

        /* Hero Section */
        .hero {
            text-align: center;
            padding: 3rem 0;
            background-color: #007bff;
            color: white;
            margin-bottom: 2rem;
            border-radius: 5px;
        }

        .hero h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        /* Featured Products */
        .featured-products {
            margin-bottom: 3rem;
        }

        .featured-products h2 {
            text-align: center;
            margin-bottom: 2rem;
            font-size: 2rem;
        }

        .category-section {
            margin-bottom: 3rem;
        }

        .category-section h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #007bff;
        }

        /* Products Grid */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
        }

        .product-card {
            background: white;
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-5px);
        }

        .product-card a {
            text-decoration: none;
            color: inherit;
        }

        .product-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .product-image-placeholder {
            width: 100%;
            height: 200px;
            background: #eee;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #777;
        }

        .product-card h4 {
            padding: 0.5rem 1rem;
            margin-top: 0.5rem;
        }

        .price {
            padding: 0 1rem;
            font-weight: bold;
            color: #007bff;
        }

        .add-to-cart {
            display: block;
            width: 100%;
            padding: 0.75rem;
            background: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s ease;
        }

        .add-to-cart:hover {
            background: #0056b3;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .products-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 480px) {
            .products-grid {
                grid-template-columns: 1fr;
            }

            .hero h1 {
                font-size: 2rem;
            }
        }
    </style>
@endsection
