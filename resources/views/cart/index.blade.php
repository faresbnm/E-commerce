@extends('layouts.app')

@section('title', 'Your Shopping Cart')
@section('content')
    <div class="cart-container">
        <h1>Your Shopping Cart</h1>

        @if (count($cart) > 0)
            <div class="cart-items">
                @foreach ($cart as $id => $details)
                    <div class="cart-item">
                        <div class="item-image">
                            @if ($details['image'])
                                <img src="{{ asset('storage/' . $details['image']) }}" alt="{{ $details['name'] }}">
                            @else
                                <div class="image-placeholder">No Image</div>
                            @endif
                        </div>

                        <div class="item-details">
                            <h3>{{ $details['name'] }}</h3>
                            <div class="price">${{ number_format($details['price'], 2) }}</div>

                            <form action="{{ route('cart.update', $id) }}" method="POST" class="quantity-form">
                                @csrf
                                @method('PATCH')
                                <div class="quantity-control">
                                    <button type="button" class="quantity-btn decrease">-</button>
                                    <input type="number" name="quantity" value="{{ $details['quantity'] }}" min="1"
                                        class="quantity-input">
                                    <button type="button" class="quantity-btn increase">+</button>
                                </div>
                                <button type="submit" class="update-btn">Update</button>
                            </form>
                        </div>

                        <div class="item-total">
                            ${{ number_format($details['price'] * $details['quantity'], 2) }}
                        </div>

                        <form action="{{ route('cart.remove', $id) }}" method="POST" class="remove-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="remove-btn">&times;</button>
                        </form>
                    </div>
                @endforeach
            </div>

            <div class="cart-summary">
                <div class="subtotal">
                    <span>Subtotal:</span>
                    <span>${{ number_format(array_sum(array_map(function ($item) {return $item['price'] * $item['quantity'];}, $cart)),2) }}</span>
                </div>

                <div class="cart-actions">
                    <a href="{{ route('shop.index') }}" class="continue-shopping">Continue Shopping</a>
                    <form action="{{ route('orders.storeFromCart') }}" method="POST">
                        @csrf
                        <button type="submit" class="checkout-btn">Place Order</button>
                    </form>
                </div>
            </div>
        @else
            <div class="empty-cart">
                <p>Your cart is empty</p>
                <a href="{{ route('shop.index') }}" class="continue-shopping">Continue Shopping</a>
            </div>
        @endif
    </div>

    <style>
        .cart-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .cart-items {
            margin: 2rem 0;
        }

        .cart-item {
            display: flex;
            align-items: center;
            padding: 1.5rem;
            border: 1px solid #eee;
            border-radius: 5px;
            margin-bottom: 1rem;
            position: relative;
        }

        .item-image {
            width: 120px;
            height: 120px;
            margin-right: 1.5rem;
        }

        .item-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .image-placeholder {
            width: 100%;
            height: 100%;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #777;
        }

        .item-details {
            flex-grow: 1;
        }

        .item-details h3 {
            margin-bottom: 0.5rem;
        }

        .price {
            font-weight: bold;
            color: #007bff;
            margin-bottom: 1rem;
        }

        .quantity-form {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .quantity-control {
            display: flex;
            align-items: center;
        }

        .quantity-btn {
            width: 30px;
            height: 30px;
            background: #f8f9fa;
            border: 1px solid #ddd;
            cursor: pointer;
        }

        .quantity-input {
            width: 50px;
            height: 30px;
            text-align: center;
            margin: 0 0.5rem;
            border: 1px solid #ddd;
        }

        .update-btn {
            padding: 0.5rem 1rem;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .item-total {
            margin: 0 2rem;
            font-weight: bold;
            font-size: 1.1rem;
        }

        .remove-form {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
        }

        .remove-btn {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #dc3545;
        }

        .cart-summary {
            border-top: 1px solid #eee;
            padding-top: 1.5rem;
            text-align: right;
        }

        .subtotal {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            font-size: 1.2rem;
            font-weight: bold;
        }

        .cart-actions {
            display: flex;
            justify-content: space-between;
        }

        .continue-shopping,
        .checkout-btn {
            padding: 0.75rem 1.5rem;
            border-radius: 4px;
            text-decoration: none;
        }

        .continue-shopping {
            background: #6c757d;
            color: white;
        }

        .checkout-btn {
            background: #007bff;
            color: white;
        }

        .empty-cart {
            text-align: center;
            padding: 2rem;
        }

        .empty-cart p {
            margin-bottom: 1rem;
            font-size: 1.2rem;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Quantity controls in cart
            document.querySelectorAll('.quantity-control').forEach(control => {
                const input = control.querySelector('.quantity-input');
                const decreaseBtn = control.querySelector('.decrease');
                const increaseBtn = control.querySelector('.increase');

                decreaseBtn.addEventListener('click', function() {
                    let value = parseInt(input.value);
                    if (value > 1) {
                        input.value = value - 1;
                    }
                });

                increaseBtn.addEventListener('click', function() {
                    let value = parseInt(input.value);
                    input.value = value + 1;
                });
            });
        });
    </script>
@endsection
