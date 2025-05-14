@extends('layouts.app')

@section('title', 'My Orders')
@section('content')
    <div class="orders-container">
        <h1>My Orders</h1>

        @if ($orders->isEmpty())
            <div class="empty-orders">
                <p>You haven't placed any orders yet.</p>
                <a href="{{ route('shop.index') }}" class="btn btn-primary">Start Shopping</a>
            </div>
        @else
            <div class="orders-list">
                @foreach ($orders as $order)
                    <div class="order-card {{ $order->status }}">
                        <div class="order-header">
                            <div class="order-meta">
                                <span class="order-number">Order #{{ $order->id }}</span>
                                <span class="order-date">{{ $order->created_at->format('M d, Y') }}</span>
                            </div>
                            <div class="order-status {{ $order->status }}">
                                {{ ucfirst($order->status) }}
                            </div>
                        </div>

                        <div class="order-items">
                            @foreach ($order->items as $item)
                                <div class="order-item">
                                    <div class="item-image">
                                        @if ($item->product->image)
                                            <img src="{{ asset('storage/' . $item->product->image) }}"
                                                alt="{{ $item->product->name }}">
                                        @else
                                            <div class="image-placeholder">No Image</div>
                                        @endif
                                    </div>
                                    <div class="item-details">
                                        <h3>{{ $item->product->name }}</h3>
                                        <div class="item-quantity">Quantity: {{ $item->quantity }}</div>
                                        <div class="item-price">${{ number_format($item->price, 2) }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="order-footer">
                            <div class="order-total">
                                Total: ${{ number_format($order->total, 2) }}
                            </div>

                            @if ($order->status === 'pending')
                                <form action="{{ route('orders.cancel', $order) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="cancel-order-btn">Cancel Order</button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <style>
        .orders-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .empty-orders {
            text-align: center;
            padding: 2rem;
        }

        .order-card {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            margin-bottom: 2rem;
            overflow: hidden;
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            background: #f8f9fa;
            border-bottom: 1px solid #e0e0e0;
        }

        .order-meta {
            display: flex;
            flex-direction: column;
        }

        .order-number {
            font-weight: bold;
        }

        .order-date {
            color: #666;
            font-size: 0.875rem;
        }

        .order-status {
            padding: 0.25rem 0.75rem;
            border-radius: 4px;
            font-weight: bold;
            font-size: 0.875rem;
        }

        .order-status.pending {
            background: #fff3cd;
            color: #856404;
        }

        .order-status.completed {
            background: #d4edda;
            color: #155724;
        }

        .order-status.cancelled {
            background: #f8d7da;
            color: #721c24;
        }

        .order-items {
            padding: 1rem;
        }

        .order-item {
            display: flex;
            gap: 1rem;
            padding: 1rem 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .order-item:last-child {
            border-bottom: none;
        }

        .item-image {
            width: 80px;
            height: 80px;
        }

        .item-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 4px;
        }

        .image-placeholder {
            width: 100%;
            height: 100%;
            background: #eee;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #777;
            border-radius: 4px;
        }

        .item-details {
            flex-grow: 1;
        }

        .item-quantity {
            color: #666;
            font-size: 0.875rem;
            margin: 0.25rem 0;
        }

        .item-price {
            font-weight: bold;
            color: #007bff;
        }

        .order-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            background: #f8f9fa;
            border-top: 1px solid #e0e0e0;
        }

        .order-total {
            font-weight: bold;
            font-size: 1.1rem;
        }

        .cancel-order-btn {
            padding: 0.5rem 1rem;
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }

        .cancel-order-btn:hover {
            background: #c82333;
        }
    </style>
@endsection
