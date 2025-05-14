@extends('layouts/app')

@section('title', 'Register')

@section('content')
    <div class="auth-container">
        <div class="auth-card">
            <h1>Register</h1>

            <form method="POST" action="{{ route('register') }}" id="registrationForm">
                @csrf

                <div class="form-group">
                    <label for="prenom">First Name</label>
                    <input id="prenom" type="text" name="prenom" value="{{ old('prenom') }}" required
                        autocomplete="given-name" autofocus>
                    @error('prenom')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="nom">Last Name</label>
                    <input id="nom" type="text" name="nom" value="{{ old('nom') }}" required
                        autocomplete="family-name">
                    @error('nom')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required
                        autocomplete="email">
                    @error('email')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="new-password">
                    @error('password')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password-confirm">Confirm Password</label>
                    <input id="password-confirm" type="password" name="password_confirmation" required
                        autocomplete="new-password">
                </div>

                <!-- Address Section -->
                <div class="address-section">
                    <h3>Addresses</h3>
                    <div id="addresses-container">
                        <div class="address-entry">
                            <div class="form-group">
                                <label>Address</label>
                                <textarea name="addresses[0][address]" class="form-control" required></textarea>
                                <div class="remember-me">
                                    <input type="radio" name="default_address" value="0" id="default_address_0"
                                        checked>
                                    <label for="default_address_0">Default Address</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" id="add-address" class="btn-add-address">+ Add Another Address</button>
                </div>

                <button type="submit" class="auth-button">Register</button>
            </form>

            <div class="auth-footer">
                Already have an account? <a href="{{ route('login') }}">Login here</a>
            </div>
        </div>
    </div>

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            let addressIndex = 1;

            // Add new address field
            $('#add-address').click(function() {
                const newAddress = `
            <div class="address-entry" data-index="${addressIndex}">
                <div class="form-group">
                    <label>Address</label>
                    <textarea name="addresses[${addressIndex}][address]" class="form-control" required></textarea>
                    <div class="remember-me">
                        <input type="radio" name="default_address" value="${addressIndex}" id="default_address_${addressIndex}">
                        <label for="default_address_${addressIndex}">Default Address</label>
                    </div>
                    <button type="button" class="btn-remove-address">Remove Address</button>
                </div>
            </div>
        `;

                $('#addresses-container').append(newAddress);
                addressIndex++;
            });

            // Remove address field
            $(document).on('click', '.btn-remove-address', function() {
                // Don't allow removing the last address
                if ($('.address-entry').length > 1) {
                    $(this).closest('.address-entry').remove();

                    // If we removed the default address, set the first one as default
                    if ($('input[name="default_address"]:checked').length === 0) {
                        $('input[name="default_address"]').first().prop('checked', true);
                    }
                }
            });
        });
    </script>

    <style>
        /* Auth Container */
        .auth-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 70vh;
            padding: 2rem 0;
        }

        /* Auth Card */
        .auth-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            width: 100%;
            max-width: 400px;
        }

        .auth-card h1 {
            text-align: center;
            margin-bottom: 1.5rem;
            color: #333;
        }

        /* Form Elements */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #555;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            border-color: #007bff;
            outline: none;
        }

        .error-message {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: block;
        }

        /* Remember Me / Default Address */
        .remember-me {
            display: flex;
            align-items: center;
            margin: 1rem 0;
        }

        .remember-me input {
            margin-right: 0.5rem;
        }

        /* Auth Button */
        .auth-button {
            width: 100%;
            padding: 0.75rem;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .auth-button:hover {
            background-color: #0056b3;
        }

        /* Links */
        .auth-footer {
            text-align: center;
            margin-top: 1.5rem;
            color: #666;
        }

        .auth-footer a {
            color: #007bff;
            text-decoration: none;
        }

        .auth-footer a:hover {
            text-decoration: underline;
        }

        /* Address Section */
        .address-section {
            margin: 2rem 0;
        }

        .address-section h3 {
            margin-bottom: 1rem;
            font-size: 1.1rem;
            color: #444;
        }

        .address-entry {
            margin-bottom: 1.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid #eee;
        }

        .address-entry:last-child {
            border-bottom: none;
        }

        .btn-add-address {
            background: #f8f9fa;
            border: 1px dashed #ccc;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
            margin: 1rem 0;
            width: 100%;
            text-align: center;
        }

        .btn-add-address:hover {
            background: #e9ecef;
        }

        .btn-remove-address {
            background: #dc3545;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 0.5rem;
            font-size: 0.875rem;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .auth-card {
                padding: 1.5rem;
                box-shadow: none;
                border: 1px solid #eee;
            }
        }
    </style>
@endsection
