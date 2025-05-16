@extends('layouts.app')

@section('title', $gallery->title)
@section('content')
    <div class="gallery-show-container">
        <h1>{{ $gallery->title }}</h1>
        <p class="gallery-meta">
            <span class="gallery-type">{{ ucfirst($gallery->type) }}</span>
            <span class="gallery-date">{{ $gallery->created_at->format('F j, Y') }}</span>
        </p>

        <!-- Main Carousel -->
        <div class="gallery-carousel">
            <div class="carousel-images">
                @foreach ($gallery->images as $image)
                    <div class="carousel-slide">
                        <img src="{{ asset('storage/' . $image->image_path) }}"
                            alt="{{ $gallery->title }} - Image {{ $loop->iteration }}">
                    </div>
                @endforeach
            </div>
            <button class="carousel-prev">❮</button>
            <button class="carousel-next">❯</button>
            <div class="carousel-indicators">
                @foreach ($gallery->images as $image)
                    <button class="indicator {{ $loop->first ? 'active' : '' }}" data-index="{{ $loop->index }}"></button>
                @endforeach
            </div>
        </div>

        <div class="gallery-description">
            <p>{{ $gallery->description }}</p>
        </div>
    </div>

    <style>
        .gallery-show-container {
            max-width: 1000px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .gallery-meta {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
            color: #666;
            font-size: 0.9rem;
        }

        .gallery-carousel {
            position: relative;
            width: 100%;
            max-height: 70vh;
            margin: 0 auto 2rem;
            overflow: hidden;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .carousel-images {
            display: flex;
            transition: transform 0.5s ease;
            height: 100%;
        }

        .carousel-slide {
            min-width: 100%;
            height: 70vh;
        }

        .carousel-slide img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            background: #f5f5f5;
        }

        .carousel-prev,
        .carousel-next {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0, 0, 0, 0.5);
            color: white;
            border: none;
            width: 50px;
            height: 50px;
            font-size: 1.5rem;
            cursor: pointer;
            border-radius: 50%;
            z-index: 10;
            transition: background 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .carousel-prev:hover,
        .carousel-next:hover {
            background: rgba(0, 0, 0, 0.8);
        }

        .carousel-prev {
            left: 20px;
        }

        .carousel-next {
            right: 20px;
        }

        .carousel-indicators {
            position: absolute;
            bottom: 20px;
            left: 0;
            right: 0;
            display: flex;
            justify-content: center;
            gap: 8px;
            z-index: 10;
        }

        .carousel-indicators .indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            border: none;
            background: rgba(255, 255, 255, 0.5);
            cursor: pointer;
            transition: background 0.3s;
        }

        .carousel-indicators .indicator.active {
            background: white;
        }

        .gallery-description {
            max-width: 800px;
            margin: 2rem auto;
            padding: 1rem;
            line-height: 1.6;
            color: #333;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const carousel = document.querySelector('.gallery-carousel');
            const images = carousel.querySelector('.carousel-images');
            const prevBtn = carousel.querySelector('.carousel-prev');
            const nextBtn = carousel.querySelector('.carousel-next');
            const indicators = carousel.querySelectorAll('.indicator');
            let currentIndex = 0;
            const totalSlides = images.children.length;
            let autoSlideInterval;

            function updateCarousel() {
                images.style.transform = `translateX(-${currentIndex * 100}%)`;

                // Update indicators
                indicators.forEach((indicator, index) => {
                    indicator.classList.toggle('active', index === currentIndex);
                });
            }

            function nextSlide() {
                currentIndex = (currentIndex + 1) % totalSlides;
                updateCarousel();
            }

            function prevSlide() {
                currentIndex = (currentIndex - 1 + totalSlides) % totalSlides;
                updateCarousel();
            }

            // Button controls
            nextBtn.addEventListener('click', nextSlide);
            prevBtn.addEventListener('click', prevSlide);

            // Indicator controls
            indicators.forEach(indicator => {
                indicator.addEventListener('click', () => {
                    currentIndex = parseInt(indicator.dataset.index);
                    updateCarousel();
                });
            });

            // Keyboard navigation
            document.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowRight') nextSlide();
                if (e.key === 'ArrowLeft') prevSlide();
            });

            // Auto-advance (optional)
            function startAutoSlide() {
                autoSlideInterval = setInterval(nextSlide, 5000);
            }

            function stopAutoSlide() {
                clearInterval(autoSlideInterval);
            }

            // Start auto-slide and pause on hover
            startAutoSlide();
            carousel.addEventListener('mouseenter', stopAutoSlide);
            carousel.addEventListener('mouseleave', startAutoSlide);

            // Touch support for mobile
            let touchStartX = 0;
            let touchEndX = 0;

            carousel.addEventListener('touchstart', (e) => {
                touchStartX = e.changedTouches[0].screenX;
                stopAutoSlide();
            }, {
                passive: true
            });

            carousel.addEventListener('touchend', (e) => {
                touchEndX = e.changedTouches[0].screenX;
                handleSwipe();
                startAutoSlide();
            }, {
                passive: true
            });

            function handleSwipe() {
                if (touchEndX < touchStartX - 50) nextSlide();
                if (touchEndX > touchStartX + 50) prevSlide();
            }
        });
    </script>
@endsection
