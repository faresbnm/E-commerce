@extends('layouts.app')

@section('content')
    <div class="pending-container">
        <h1 class="pending-title">Pending Product Approvals</h1>

        @if ($products->isEmpty())
            <p class="no-products">No products pending approval</p>
        @else
            <div class="table-wrapper">
                <table class="pending-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Submitted By</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td class="product-cell">
                                    <div class="product-info">
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                            class="product-image">
                                        <span class="product-name">{{ $product->name }}</span>
                                    </div>
                                </td>
                                <td class="price-cell">${{ number_format($product->price, 2) }}</td>
                                <td class="submitter-cell">{{ $product->user?->nom ?? 'Unknown' }}</td>
                                <td class="actions-cell">
                                    <div class="action-buttons">
                                        <form action="{{ route('admin.products.approve', $product) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="approve-btn">
                                                Approve
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.products.reject', $product) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="reject-btn">
                                                Reject
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <style>
        /* Base Styles */
        .pending-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .pending-title {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 2rem;
            font-size: 1.8rem;
        }

        .no-products {
            text-align: center;
            color: #7f8c8d;
            padding: 2rem;
            background: #f8f9fa;
            border-radius: 4px;
        }

        .table-wrapper {
            overflow-x: auto;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .pending-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        .pending-table th {
            background: #34495e;
            color: white;
            padding: 1rem;
            text-align: left;
            font-weight: 500;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
        }

        .pending-table td {
            padding: 1rem;
            border-bottom: 1px solid #ecf0f1;
            vertical-align: middle;
        }

        .pending-table tr:hover {
            background-color: #f8f9fa;
        }

        .product-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .product-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 4px;
        }

        .product-name {
            font-weight: 500;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .approve-btn,
        .reject-btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.8rem;
            transition: all 0.2s ease;
        }

        .approve-btn {
            background-color: #27ae60;
            color: white;
        }

        .approve-btn:hover {
            background-color: #2ecc71;
        }

        .reject-btn {
            background-color: #e74c3c;
            color: white;
        }

        .reject-btn:hover {
            background-color: #c0392b;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .pending-table {
                display: block;
            }

            .pending-table thead {
                display: none;
            }

            .pending-table tbody,
            .pending-table tr,
            .pending-table td {
                display: block;
                width: 100%;
            }

            .pending-table tr {
                margin-bottom: 1rem;
                border: 1px solid #ddd;
                border-radius: 8px;
                padding: 0.5rem;
            }

            .pending-table td {
                padding: 0.75rem 0.5rem;
                border-bottom: 1px solid #eee;
                display: flex;
                justify-content: space-between;
            }

            .pending-table td::before {
                content: attr(data-label);
                font-weight: bold;
                margin-right: 1rem;
                text-transform: uppercase;
                font-size: 0.75rem;
                color: #7f8c8d;
            }

            .product-cell {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }

            .product-info {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }

            .actions-cell .action-buttons {
                width: 100%;
                justify-content: flex-end;
            }
        }

        @media (max-width: 480px) {
            .pending-title {
                font-size: 1.5rem;
            }

            .approve-btn,
            .reject-btn {
                padding: 0.4rem 0.8rem;
                font-size: 0.7rem;
            }

            .pending-container {
                padding: 0 0.5rem;
            }
        }
    </style>

    <script>
        // Add data-labels for mobile view
        document.addEventListener('DOMContentLoaded', function() {
            const headers = document.querySelectorAll('.pending-table thead th');
            const rows = document.querySelectorAll('.pending-table tbody tr');

            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                cells.forEach((cell, index) => {
                    if (headers[index]) {
                        cell.setAttribute('data-label', headers[index].textContent);
                    }
                });
            });
        });
    </script>
@endsection
