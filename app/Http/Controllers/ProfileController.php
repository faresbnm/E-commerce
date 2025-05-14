<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $addresses = $user->addresses;
        return view('user.edit', compact('user', 'addresses'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'prenom' => ['required', 'string', 'max:255'],
            'nom' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 
                        Rule::unique('users')->ignore($user->id)],
        ]);
        $user->update($validated);

        return redirect()->route('profile.edit')
            ->with('success', 'Profile updated successfully');
    }

    public function updatePassword(Request $request)
    {
    $request->validate([
        'current_password' => 'required',
        'new_password' => 'required|string|min:8|confirmed',
    ]);

    $user = auth()->user();

    // Verify current password
    if (!Hash::check($request->current_password, $user->password)) {
        return back()->withErrors(['current_password' => 'Current password is incorrect']);
    }

    // Update password
    $user->password = Hash::make($request->new_password);
    $user->save();

    return back()->with('success', 'Password updated successfully');
    }

    public function addAddress(Request $request)
    {
        $validated = $request->validate([
            'address' => ['required', 'string', 'max:500'],
            'is_default' => ['sometimes', 'boolean']
        ]);

        // If setting as default, remove default status from other addresses
        if ($request->is_default) {
            Auth::user()->addresses()->update(['is_default' => false]);
        }

        Auth::user()->addresses()->create($validated);

        return redirect()->route('profile.edit')
            ->with('success', 'Address added successfully');
    }

    public function deleteAddress(Address $address)
    {
        $this->authorize('delete', $address);
        
        $address->delete();
        
        return redirect()->route('profile.edit')
            ->with('success', 'Address removed successfully');
    }

    public function setDefaultAddress(Address $address)
    {
        $this->authorize('update', $address);
        
        Auth::user()->addresses()->update(['is_default' => false]);
        $address->update(['is_default' => true]);
        
        return redirect()->route('profile.edit')
            ->with('success', 'Default address updated');
    }
}