<!DOCTYPE html>
<html>

<head>
    <title>Order Confirmation #{{ $order->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
        }

        .content {
            padding: 20px;
        }

        .footer {
            margin-top: 20px;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #777;
        }

        .order-item {
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Order Confirmation #{{ $order->id }}</h1>
        </div>

        <div class="content">
            <p>Thank you for your order! Here are your order details:</p>

            <p><strong>Order Date:</strong> {{ $order->created_at->format('F j, Y') }}</p>
            <p><strong>Order Total:</strong> ${{ number_format($order->total, 2) }}</p>
            <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>

            <h3>Order Items:</h3>
            @foreach ($order->items as $item)
                <div class="order-item">
                    <p>{{ $item->product->name }} (Qty: {{ $item->quantity }})
                    </p>
                </div>
            @endforeach

            <p>You can view your order anytime by logging into your account.</p>
        </div>

        <div class="footer">
            <p>Thanks,<br>{{ config('app.name') }} Team</p>
        </div>
    </div>
</body>

</html>
