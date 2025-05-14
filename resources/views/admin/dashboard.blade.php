@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('content')
    <div class="admin-container">
        <h1>Admin Dashboard</h1>

        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Sales</h3>
                <p>${{ number_format($stats['total_sales'], 2) }}</p>
            </div>
            <div class="stat-card">
                <h3>Total Orders</h3>
                <p>{{ $stats['total_orders'] }}</p>
            </div>
            <div class="stat-card">
                <h3>Pending Approvals</h3>
                <p>{{ $stats['pending_approvals'] }}</p>
            </div>
        </div>

        <div class="admin-links">
            <a href="{{ route('admin.users') }}" class="admin-link">User Management</a>
            <a href="{{ route('admin.approvals') }}" class="admin-link">Pending Approvals</a>
        </div>
    </div>

    <style>
        .admin-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin: 2rem 0;
        }

        .stat-card {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .stat-card h3 {
            color: #555;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }

        .stat-card p {
            font-size: 1.5rem;
            font-weight: bold;
            color: #007bff;
        }

        .admin-links {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        .admin-link {
            padding: 1rem 1.5rem;
            background: #007bff;
            color: white;
            border-radius: 4px;
            text-decoration: none;
        }

        .admin-link:hover {
            background: #0056b3;
        }
    </style>
@endsection
