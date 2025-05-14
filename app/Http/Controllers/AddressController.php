<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function index()
    {
        $addresses = Auth::user()->addresses()->latest()->get();
        return view('addresses.index', compact('addresses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'address' => 'required|string|max:500',
            'is_default' => 'sometimes|boolean'
        ]);

        // If setting as default, remove default status from other addresses
        if ($request->is_default) {
            Auth::user()->addresses()->update(['is_default' => false]);
        }

        Auth::user()->addresses()->create($validated);
        
        return redirect()->route('addresses.index')
            ->with('success', 'Address added successfully');
    }

    public function update(Request $request, Address $address)
    {
        $this->authorize('update', $address);

        $validated = $request->validate([
            'address' => 'required|string|max:500',
            'is_default' => 'sometimes|boolean'
        ]);

        // If setting as default, remove default status from other addresses
        if ($request->is_default) {
            Auth::user()->addresses()
                ->where('id', '!=', $address->id)
                ->update(['is_default' => false]);
        }

        $address->update($validated);
        
        return redirect()->route('addresses.index')
            ->with('success', 'Address updated successfully');
    }

    public function destroy(Address $address)
    {
        $this->authorize('delete', $address);
        $address->delete();
        return redirect()->route('addresses.index')
            ->with('success', 'Address removed');
    }

    public function setDefault(Address $address)
    {
        $this->authorize('update', $address);
        
        Auth::user()->addresses()->update(['is_default' => false]);
        $address->update(['is_default' => true]);
        
        return redirect()->route('addresses.index')
            ->with('success', 'Default address updated');
    }
}