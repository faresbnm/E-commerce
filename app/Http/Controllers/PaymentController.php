<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $payments = Auth::user()->payments()->latest()->get();
        return view('payments.index', compact('payments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'card_holder' => 'required|string|max:255',
            'card_number' => 'required|numeric|digits:16',
            'expiry_date' => 'required|date_format:m/y|after:today',
            'cvv' => 'required|numeric|digits:3'
        ]);

        // Mask the card number before storing
        $validated['card_number'] = substr($validated['card_number'], -4);

        Auth::user()->payments()->create($validated);
        
        return redirect()->route('payments.index')->with('success', 'Payment method added successfully');
    }

    public function destroy(Payment $payment)
    {
        $this->authorize('delete', $payment);
        $payment->delete();
        return redirect()->route('payments.index')->with('success', 'Payment method removed');
    }
}