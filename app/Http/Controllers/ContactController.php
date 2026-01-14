<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    /**
     * Handle contact form submission
     */
    public function submit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|in:general,technical,billing,feature,bug,partnership',
            'message' => 'required|string|max:5000',
        ]);

        // Create the contact message in the database
        \App\Models\ContactMessage::create($validated);

        // Log for redundancy
        Log::info('Contact Form Submission Saved:', $validated);

        // Flash success message
        return back()->with('success', 'Thank you for contacting us! We have received your message and will get back to you shortly.');
    }
}
