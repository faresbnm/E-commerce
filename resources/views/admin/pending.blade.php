@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Pending Product Approvals</h1>

        @if ($products->isEmpty())
            <p>No products pending approval</p>
        @else
            <table class="table">
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
                            <td>
                                <img src="{{ asset('storage/' . $product->image) }}" width="50" class="mr-2">
                                {{ $product->name }}
                            </td>
                            <td>${{ number_format($product->price, 2) }}</td>
                            <td>{{ $product->user->name }}</td>
                            <td>
                                <form action="{{ route('admin.products.approve', $product) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">Approve</button>
                                </form>
                                <form action="{{ route('admin.products.reject', $product) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
