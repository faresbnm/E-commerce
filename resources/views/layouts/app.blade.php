<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - @yield('title')</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

</head>

<body>
    <header>
        <div class="container">
            <nav>
                <a href="{{ route('home') }}" class="logo">{{ config('app.name', 'Laravel') }}</a>
                <ul class="nav-links">
                    <li><a href="{{ route('shop.index') }}">Shop</a></li>
                    @guest
                        <li><a href="{{ route('login') }}">Login</a></li>
                        <li><a href="{{ route('register') }}">Register</a></li>
                    @else
                        <li><a href="{{ route('orders.index') }}">My Orders</a></li>
                        <li><a href="{{ route('profile.edit') }}">My Profile</a></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit"
                                    style="background: none; border: none; color: #333; cursor: pointer; font-weight: 500;">Logout</button>
                            </form>
                        </li>
                        <li><a href="{{ route('cart.index') }}">
                                Cart
                                @if (count((array) session('cart')))
                                    <span class="cart-count">{{ count((array) session('cart')) }}</span>
                                @endif
                            </a></li>
                    @endguest
                    @auth
                        @if (auth()->user()->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.dashboard') }}">Admin Dashboard</a>
                            </li>
                        @endif

                        @if (auth()->user()->isItCommercial())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('products.create') }}">Add Product</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('gallery.create') }}">Add Gallery Item</a>
                            </li>
                        @endif
                    @endauth
                </ul>
            </nav>
        </div>
    </header>

    <main class="container">
        @yield('content')
    </main>

    <footer>
        <div class="container">
            <p>&copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.</p>
        </div>
    </footer>
</body>

</html>
