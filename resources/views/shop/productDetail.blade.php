@extends('layouts.app')

@section('title', $product->name)
@section('content')
    <div class="product-detail-container">
        <div class="product-images">
            @if ($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="main-image">
            @else
                <div class="image-placeholder">No Image Available</div>
            @endif
        </div>

        <div class="product-info">
            <h1>{{ $product->name }}</h1>
            <div class="price">$<span id="product-price">{{ number_format($product->price, 2) }}</span></div>
            <div class="description">{{ $product->description }}</div>

            @auth
                <div class="cart-form">
                    <form action="{{ route('cart.add', $product) }}" method="POST" id="add-to-cart-form">
                        @csrf
                        <div class="form-group">
                            <label for="quantity">Quantity:</label>
                            <div class="quantity-control">
                                <button type="button" class="quantity-btn" id="decrease-qty">-</button>
                                <input type="number" id="quantity" name="quantity" min="1" value="1"
                                    class="quantity-input">
                                <button type="button" class="quantity-btn" id="increase-qty">+</button>
                            </div>
                        </div>

                        <div class="total-price">
                            Total: $<span id="total-price">{{ number_format($product->price, 2) }}</span>
                        </div>

                        <button type="submit" class="add-to-cart-btn">
                            <i class="fas fa-shopping-cart"></i> Add to Cart
                        </button>
                    </form>
                </div>

                <div class="reviews-section">
                    <h3>Customer Reviews</h3>

                    @auth
                        <div class="add-review">
                            <h4>Write a Review</h4>
                            <form action="{{ route('reviews.store', $product) }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label>Rating:</label>
                                    <div class="star-rating">
                                        @for ($i = 5; $i >= 1; $i--)
                                            <input type="radio" id="star{{ $i }}" name="rating"
                                                value="{{ $i }}" {{ old('rating') == $i ? 'checked' : '' }}>
                                            <label for="star{{ $i }}">★</label>
                                        @endfor
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="comment">Comment (optional):</label>
                                    <textarea id="comment" name="comment" class="form-control" rows="3">{{ old('comment') }}</textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Submit Review</button>
                            </form>
                        </div>
                    @else
                        <div class="login-prompt">
                            <a href="{{ route('login') }}">Login</a> to write a review
                        </div>
                    @endauth

                    <div class="reviews-list">
                        @foreach ($product->reviews()->with('user')->latest()->get() as $review)
                            <div class="review-item">
                                <div class="review-header">
                                    <div class="review-user">{{ $review->user->name }}</div>
                                    <div class="review-rating">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= $review->rating)
                                                ★
                                            @else
                                                ☆
                                            @endif
                                        @endfor
                                    </div>
                                    <div class="review-date">{{ $review->created_at->format('M d, Y') }}</div>
                                </div>
                                @if ($review->comment)
                                    <div class="review-comment">{{ $review->comment }}</div>
                                @endif
                            </div>
                        @endforeach

                        @if ($product->reviews->isEmpty())
                            <div class="no-reviews">No reviews yet. Be the first to review!</div>
                        @endif
                    </div>
                </div>
            @else
                <div class="login-prompt">
                    <a href="{{ route('login') }}" class="login-link">Login</a> to add items to your cart
                </div>
            @endauth
        </div>
    </div>

    <style>
        /* Reviews Section */
        .reviews-section {
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 1px solid #eee;
        }

        .add-review {
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .star-rating {
            display: flex;
            flex-direction: row-reverse;
            justify-content: flex-end;
            margin: 0.5rem 0;
        }

        .star-rating input {
            display: none;
        }

        .star-rating label {
            font-size: 1.5rem;
            color: #ccc;
            cursor: pointer;
            padding: 0 0.1rem;
        }

        .star-rating input:checked~label,
        .star-rating input:hover~label {
            color: #ffc107;
        }

        .reviews-list {
            margin-top: 2rem;
        }

        .review-item {
            padding: 1rem 0;
            border-bottom: 1px solid #eee;
        }

        .review-header {
            display: flex;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .review-user {
            font-weight: bold;
            margin-right: 1rem;
        }

        .review-rating {
            color: #ffc107;
            margin-right: 1rem;
        }

        .review-date {
            color: #777;
            font-size: 0.875rem;
        }

        .review-comment {
            margin-top: 0.5rem;
            line-height: 1.5;
        }

        .no-reviews {
            color: #777;
            font-style: italic;
        }

        .product-detail-container {
            display: flex;
            gap: 2rem;
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .product-images {
            flex: 1;
        }

        .product-info {
            flex: 1;
        }

        .main-image {
            width: 100%;
            max-height: 500px;
            object-fit: contain;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .image-placeholder {
            width: 100%;
            height: 300px;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            color: #777;
        }

        .product-info h1 {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: #333;
        }

        .price {
            font-size: 1.5rem;
            font-weight: bold;
            color: #007bff;
            margin: 1rem 0;
        }

        .description {
            margin: 1.5rem 0;
            line-height: 1.6;
            color: #555;
        }

        .cart-form {
            margin-top: 2rem;
            padding: 1.5rem;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #555;
        }

        .quantity-control {
            display: flex;
            align-items: center;
        }

        .quantity-btn {
            width: 40px;
            height: 40px;
            background: #f8f9fa;
            border: 1px solid #ddd;
            font-size: 1rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .quantity-btn:hover {
            background: #e9ecef;
        }

        .quantity-input {
            width: 60px;
            height: 40px;
            text-align: center;
            margin: 0 0.5rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }

        .total-price {
            font-size: 1.2rem;
            font-weight: bold;
            margin: 1.5rem 0;
            padding: 0.75rem;
            background: #f8f9fa;
            border-radius: 4px;
        }

        .add-to-cart-btn {
            width: 100%;
            padding: 0.75rem;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .add-to-cart-btn:hover {
            background: #218838;
        }

        .login-prompt {
            margin-top: 2rem;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 4px;
            text-align: center;
        }

        .login-link {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }

        .login-link:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .product-detail-container {
                flex-direction: column;
            }
        }
    </style>

    @auth
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const quantityInput = document.getElementById('quantity');
                const decreaseBtn = document.getElementById('decrease-qty');
                const increaseBtn = document.getElementById('increase-qty');
                const productPrice = parseFloat(document.getElementById('product-price').textContent.replace(
                    /[^0-9.-]/g, ''));
                const totalPriceElement = document.getElementById('total-price');
                const addToCartForm = document.getElementById('add-to-cart-form');

                // Quantity control buttons
                decreaseBtn.addEventListener('click', function() {
                    let value = parseInt(quantityInput.value);
                    if (value > 1) {
                        quantityInput.value = value - 1;
                        updateTotalPrice();
                    }
                });

                increaseBtn.addEventListener('click', function() {
                    let value = parseInt(quantityInput.value);
                    quantityInput.value = value + 1;
                    updateTotalPrice();
                });

                // Update total price when quantity changes
                quantityInput.addEventListener('input', updateTotalPrice);

                function updateTotalPrice() {
                    const quantity = parseInt(quantityInput.value) || 1;
                    const totalPrice = (productPrice * quantity).toFixed(2);
                    totalPriceElement.textContent = totalPrice;
                }

                // Handle form submission with AJAX
                addToCartForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const form = this;
                    const formData = new FormData(form);
                    const submitButton = form.querySelector('button[type="submit"]');
                    const originalButtonText = submitButton.innerHTML;

                    // Show loading state
                    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';
                    submitButton.disabled = true;

                    fetch(form.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            }
                        })
                        .then(async response => {
                            const contentType = response.headers.get('content-type');
                            if (!contentType || !contentType.includes('application/json')) {
                                const text = await response.text();
                                throw new Error('Server returned non-JSON response');
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                updateCartCount(data.cart_count);
                                showAlert('Product added to cart!', 'success');
                                setTimeout(() => {
                                    quantityInput.value = 1;
                                    updateTotalPrice();
                                }, 1000);
                            } else {
                                showAlert(data.message || 'Failed to add to cart', 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showAlert('Failed to add to cart. Please try again.', 'error');
                        })
                        .finally(() => {
                            submitButton.innerHTML = originalButtonText;
                            submitButton.disabled = false;
                        });
                });

                function updateCartCount(count) {
                    const cartCountElement = document.querySelector('.cart-count');
                    if (cartCountElement) {
                        cartCountElement.textContent = count;
                    } else {
                        // Create cart count element if it doesn't exist
                        const cartLink = document.querySelector('.cart-link');
                        if (cartLink) {
                            const countElement = document.createElement('span');
                            countElement.className = 'cart-count';
                            countElement.textContent = count;
                            cartLink.appendChild(countElement);
                        }
                    }
                }

                function showAlert(message, type) {
                    // Create or reuse alert element
                    let alertElement = document.getElementById('cart-alert');

                    if (!alertElement) {
                        alertElement = document.createElement('div');
                        alertElement.id = 'cart-alert';
                        alertElement.style.position = 'fixed';
                        alertElement.style.top = '20px';
                        alertElement.style.right = '20px';
                        alertElement.style.padding = '1rem 1.5rem';
                        alertElement.style.borderRadius = '4px';
                        alertElement.style.color = 'white';
                        alertElement.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.2)';
                        alertElement.style.zIndex = '1000';
                        document.body.appendChild(alertElement);
                    }

                    alertElement.textContent = message;
                    alertElement.style.backgroundColor = type === 'success' ? '#28a745' : '#dc3545';
                    alertElement.style.display = 'block';

                    // Hide after 3 seconds
                    setTimeout(() => {
                        alertElement.style.display = 'none';
                    }, 3000);
                }
            });
        </script>
    @endauth
@endsection
