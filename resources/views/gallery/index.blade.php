@extends('layouts.app')

@section('title', 'Gallery')
@section('content')
    <div class="gallery-container">
        <h1>Gallery</h1>
        <div class="gallery-grid">
            @forelse($galleries as $gallery)
                <div class="gallery-item">
                    <a href="{{ route('gallery.show', $gallery) }}">
                        <img src="{{ asset('storage/' . $gallery->featured_image) }}" alt="{{ $gallery->title }}">
                        <div class="gallery-info">
                            <h3>{{ $gallery->title }}</h3>
                            <p>{{ Str::limit($gallery->description, 100) }}</p>
                            <small>Images: {{ $gallery->images->count() }}</small>
                            @if (auth()->check() && auth()->user()->isItCommercial())
                                <form action="{{ route('gallery.destroy', $gallery->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn"
                                        onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            @endif
                        </div>
                    </a>
                </div>
            @empty
                <p>No galleries found.</p>
            @endforelse
        </div>

        {{ $galleries->links() }}
    </div>

    <style>
        .gallery-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1rem;
        }

        .gallery-grid {
            display: grid;
            gap: 1.5rem;
        }

        .gallery-item {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .gallery-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .gallery-item a {
            text-decoration: none;
            color: inherit;
        }

        .gallery-item img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            display: block;
        }

        .gallery-info {
            padding: 1rem;
        }

        .gallery-info h3 {
            margin: 0 0 0.5rem 0;
            color: #333;
            font-size: 1.1rem;
        }

        .gallery-info p {
            margin: 0 0 0.5rem 0;
            color: #666;
            font-size: 0.9rem;
            line-height: 1.4;
        }

        .gallery-info small {
            color: #888;
            font-size: 0.8rem;
        }

        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 2rem;
            padding: 1rem 0;
        }

        .pagination .page-item {
            margin: 0 0.25rem;
        }

        .pagination .page-link {
            padding: 0.5rem 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            color: #007bff;
            text-decoration: none;
        }

        .pagination .page-item.active .page-link {
            background: #007bff;
            color: white;
            border-color: #007bff;
        }

        .pagination .page-item.disabled .page-link {
            color: #6c757d;
            pointer-events: none;
        }

        /* Small devices (phones, 576px and below) */
        @media (max-width: 576px) {
            .gallery-container {
                padding: 0.5rem;
            }

            .gallery-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .gallery-item {
                margin-bottom: 1rem;
            }

            .gallery-info h3 {
                font-size: 1rem;
            }

            .gallery-info p {
                font-size: 0.8rem;
            }
        }

        /* Medium devices (tablets, 577px to 768px) */
        @media (min-width: 577px) and (max-width: 768px) {
            .gallery-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1.25rem;
            }
        }

        /* Large devices (small laptops, 769px to 992px) */
        @media (min-width: 769px) and (max-width: 992px) {
            .gallery-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 1.5rem;
            }
        }

        /* Extra large devices (desktops, 993px to 1200px) */
        @media (min-width: 993px) and (max-width: 1200px) {
            .gallery-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        /* Super large devices (large desktops, 1201px and up) */
        @media (min-width: 1201px) {
            .gallery-grid {
                grid-template-columns: repeat(5, 1fr);
            }
        }

        /* Mobile landscape orientation */
        @media (max-width: 768px) and (orientation: landscape) {
            .gallery-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .gallery-item img {
                max-height: 150px;
            }
        }

        /* High DPI devices */
        @media (-webkit-min-device-pixel-ratio: 2),
        (min-resolution: 192dpi) {
            .gallery-item img {
                border: 1px solid rgba(0, 0, 0, 0.05);
            }
        }
    </style>
@endsection
