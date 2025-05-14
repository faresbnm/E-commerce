@extends('layouts/app')

@section('title', 'Edit Profile')

@section('content')
    <div class="profile-container">
        <div class="profile-card">
            <h1>Edit Profile</h1>

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="prenom">First Name</label>
                    <input id="prenom" type="text" name="prenom" value="{{ old('prenom', $user->prenom) }}" required>
                </div>

                <div class="form-group">
                    <label for="nom">Last Name</label>
                    <input id="nom" type="text" name="nom" value="{{ old('nom', $user->nom) }}" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required>
                </div>

                <button type="submit" class="btn-update">Update Profile</button>
            </form>
            <div class="password-update-section">
                <h3>Change Password</h3>
                <form action="{{ route('profile.updatePassword') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="current_password">Current Password</label>
                        <input type="password" id="current_password" name="current_password" required class="form-control">
                        @error('current_password')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="new_password">New Password</label>
                        <input type="password" id="new_password" name="new_password" required minlength="8"
                            class="form-control">
                        @error('new_password')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="new_password_confirmation">Confirm New Password</label>
                        <input type="password" id="new_password_confirmation" name="new_password_confirmation" required
                            class="form-control">
                    </div>

                    <button type="submit" class="btn-update">Rest Password</button>
                </form>
            </div>

            <div class="address-section">
                <h2>Your Addresses</h2>

                @foreach ($addresses as $address)
                    <div class="address-card {{ $address->is_default ? 'default' : '' }}">
                        <p>{{ $address->address }}</p>
                        <div class="address-actions">
                            @if (!$address->is_default)
                                <form action="{{ route('address.set-default', $address) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn-set-default">Set as Default</button>
                                </form>
                            @else
                                <span class="default-badge">Default Address</span>
                            @endif
                            <form action="{{ route('address.delete', $address) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete">Delete</button>
                            </form>
                        </div>
                    </div>
                @endforeach

                <h3>Add New Address</h3>
                <form action="{{ route('address.add') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <textarea name="address" required placeholder="Enter your address"></textarea>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="is_default" id="is_default">
                        <label for="is_default">Set as default address</label>
                    </div>
                    <button type="submit" class="btn-add">Add Address</button>
                </form>
            </div>
        </div>
    </div>

    <style>
        .profile-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .profile-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 2rem;
        }

        .address-card {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .address-card.default {
            border-left: 4px solid #007bff;
        }

        .address-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }

        .btn-update,
        .btn-add {
            background: #007bff;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-set-default {
            background: #28a745;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-delete {
            background: #dc3545;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
        }

        .default-badge {
            background: #007bff;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            font-size: 0.875rem;
        }
    </style>
@endsection
