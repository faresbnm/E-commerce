@extends('layouts.app')

@section('title', 'Add Gallery')
@section('content')
    <div class="gallery-form-container">
        <h1>Add Gallery</h1>

        <form action="{{ route('gallery.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" name="title" id="title" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" required></textarea>
            </div>

            <div class="form-group">
                <label for="images">Images (Multiple)</label>
                <input type="file" name="images[]" id="images" multiple required accept="image/*">
            </div>

            <button type="submit" class="submit-btn">Submit for Approval</button>
        </form>
    </div>

    <style>
        .gallery-form-container {
            max-width: 600px;
            margin: 2rem auto;
            padding: 1.5rem;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .gallery-form-container h1 {
            text-align: center;
            margin-bottom: 1.5rem;
            color: #333;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #444;
        }

        .form-group input[type="text"],
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        .form-group input[type="text"]:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            border-color: #4CAF50;
            outline: none;
        }

        .form-group textarea {
            min-height: 120px;
            resize: vertical;
        }

        .form-group input[type="file"] {
            width: 100%;
            padding: 0.5rem;
            border: 1px dashed #ccc;
            border-radius: 4px;
            background: #f9f9f9;
        }

        .submit-btn {
            display: block;
            width: 100%;
            padding: 0.75rem;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .submit-btn:hover {
            background-color: #3d8b40;
        }

        /* Style for file input */
        .form-group input[type="file"]::file-selector-button {
            padding: 0.5rem 1rem;
            background: #e9e9e9;
            border: 1px solid #ccc;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 1rem;
        }

        .form-group input[type="file"]::file-selector-button:hover {
            background: #ddd;
        }

        /* Small devices (phones, 576px and up) */
        @media (max-width: 576px) {
            .gallery-form-container {
                padding: 1rem;
                margin: 1rem;
            }

            .form-group input[type="text"],
            .form-group textarea,
            .form-group select {
                padding: 0.6rem;
            }

            .submit-btn {
                padding: 0.6rem;
            }
        }

        /* Medium devices (tablets, 768px and up) */
        @media (min-width: 577px) and (max-width: 768px) {
            .gallery-form-container {
                max-width: 500px;
            }
        }

        /* Large devices (desktops, 992px and up) */
        @media (min-width: 769px) and (max-width: 992px) {
            .gallery-form-container {
                max-width: 700px;
            }
        }

        /* Extra large devices (large desktops, 1200px and up) */
        @media (min-width: 1200px) {
            .gallery-form-container {
                max-width: 800px;
            }
        }

        /* Mobile landscape orientation */
        @media (max-width: 768px) and (orientation: landscape) {
            .gallery-form-container {
                margin: 0.5rem auto;
                padding: 1rem;
            }

            .form-group textarea {
                min-height: 80px;
            }
        }

        /* High resolution displays */
        @media (-webkit-min-device-pixel-ratio: 2),
        (min-resolution: 192dpi) {
            .submit-btn {
                border-width: 1px;
            }
        }
    </style>
@endsection
