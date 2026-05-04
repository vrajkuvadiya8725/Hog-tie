<?php

namespace App\Http\Controllers;

use App\Models\BulkInquiry;
use Illuminate\Http\Request;

class BulkInquiryController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:25'],
            'quantity' => ['required', 'integer', 'min:1'],
            'address_line' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:120'],
            'state' => ['required', 'string', 'max:120'],
            'postal_code' => ['required', 'string', 'max:20'],
            'note' => ['nullable', 'string', 'max:2000'],
        ]);

        BulkInquiry::create($validated);

        return back()->with('success', 'Your bulk order inquiry has been received.');
    }
}
