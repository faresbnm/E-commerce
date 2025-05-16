@extends('layouts.app')

@section('title', 'Pending Gallery Approvals')
@section('content')
    <div class="admin-container">
        <h1>Pending Gallery Approvals</h1>

        <div class="gallery-approval-list">
            @forelse($galleries as $gallery)
                <div class="gallery-approval-item">
                    <div class="gallery-preview">
                        <!-- Carousel Container -->
                        <div class="gallery-carousel" id="carousel-{{ $gallery->id }}">
                            <div class="carousel-images">
                                @foreach ($gallery->images as $image)
                                    <div class="carousel-slide">
                                        <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $gallery->title }}">
                                    </div>
                                @endforeach
                            </div>
                            <button class="carousel-prev">❮</button>
                            <button class="carousel-next">❯</button>
                        </div>

                        <div class="gallery-info">
                            <h3>{{ $gallery->title }}</h3>
                            <p>{{ Str::limit($gallery->description, 150) }}</p>
                            <small>Submitted by: {{ $gallery->user->nom }}</small> <br>
                            <small>Images: {{ $gallery->images->count() }}</small>
                        </div>
                    </div>
                    <div class="approval-actions">
                        <form action="{{ route('admin.gallery.approve', $gallery) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-approve">Approve</button>
                        </form>
                        <form action="{{ route('admin.gallery.reject', $gallery) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-reject">Reject</button>
                        </form>
                    </div>
                </div>
            @empty
                <p>No pending gallery approvals.</p>
            @endforelse
        </div>

        {{ $galleries->links() }}
    </div>


    <style>
        .gallery-approval-list {
            margin-top: 2rem;
        }

        .gallery-approval-item {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .gallery-preview {
            display: flex;
            gap: 1.5rem;
            margin-bottom: 1rem;
        }

        .gallery-preview img {
            width: 200px;
            height: 150px;
            object-fit: cover;
            border-radius: 4px;
        }

        .gallery-info {
            flex: 1;
        }

        .approval-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
        }

        .btn-approve,
        .btn-reject,
        .btn-view {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-approve {
            background: #4CAF50;
            color: white;
        }

        .btn-reject {
            background: #f44336;
            color: white;
        }

        .btn-view {
            background: #2196F3;
            color: white;
        }

        .gallery-carousel {
            position: relative;
            width: 300px;
            height: 200px;
            overflow: hidden;
            border-radius: 8px;
        }

        .carousel-images {
            display: flex;
            transition: transform 0.5s ease;
            height: 100%;
        }

        .carousel-slide {
            min-width: 100%;
            height: 100%;
        }

        .carousel-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .carousel-prev,
        .carousel-next {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0, 0, 0, 0.5);
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 50%;
            z-index: 10;
        }

        .carousel-prev {
            left: 10px;
        }

        .carousel-next {
            right: 10px;
        }

        .carousel-prev:hover,
        .carousel-next:hover {
            background: rgba(0, 0, 0, 0.8);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize carousels for each gallery
            document.querySelectorAll('.gallery-carousel').forEach(carousel => {
                const images = carousel.querySelector('.carousel-images');
                const prevBtn = carousel.querySelector('.carousel-prev');
                const nextBtn = carousel.querySelector('.carousel-next');
                let currentIndex = 0;
                const totalSlides = images.children.length;

                // Update carousel position
                function updateCarousel() {
                    images.style.transform = `translateX(-${currentIndex * 100}%)`;
                }

                // Next slide
                nextBtn.addEventListener('click', () => {
                    currentIndex = (currentIndex + 1) % totalSlides;
                    updateCarousel();
                });

                // Previous slide
                prevBtn.addEventListener('click', () => {
                    currentIndex = (currentIndex - 1 + totalSlides) % totalSlides;
                    updateCarousel();
                });

                // Initialize
                updateCarousel();
            });
        });
    </script>
@endsection
