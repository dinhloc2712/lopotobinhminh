<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Store a new contact submission from the frontend.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
        ]);

        Contact::create($validated);

        return back()->with('success', 'Cảm ơn bạn đã để lại thông tin. Vinayuuki sẽ liên hệ lại với bạn sớm nhất!');
    }
}
