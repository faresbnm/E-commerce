@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>User Management</h1>

        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->nom }} {{ $user->prenom }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->role }}</td>
                        <td>
                            <form action="{{ route('admin.users.delete', $user) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <style>
        /* Base Styles */
        .container {
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        h1 {
            font-size: 1.8rem;
            margin-bottom: 1.5rem;
            color: #2d3748;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .table th {
            background-color: #f8f9fa;
            padding: 12px;
            text-align: left;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
        }

        .table td {
            padding: 12px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: middle;
        }

        .table tr:hover {
            background-color: #f8f9fa;
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        /* Role Badge */
        td:nth-child(3) {
            font-weight: 500;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }

            .table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }

            h1 {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 576px) {

            .table th,
            .table td {
                padding: 8px;
                font-size: 0.9rem;
            }

            .btn-danger {
                padding: 6px 12px;
                font-size: 0.8rem;
            }
        }
    </style>
@endsection
