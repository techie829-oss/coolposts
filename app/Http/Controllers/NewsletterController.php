<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    /**
     * Handle newsletter subscription.
     */
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
        ]);

        $email = $request->input('email');
        $ip = $request->ip();

        // Check if subscriber already exists
        $subscriber = Subscriber::where('email', $email)->first();

        if ($subscriber) {
            // If exists but inactive, reactivate it
            if (!$subscriber->is_active) {
                $subscriber->update([
                    'is_active' => true,
                    'ip_address' => $ip,
                ]);
            }

            return back()->with('success', 'You are already subscribed to our newsletter!');
        }

        // Create new subscriber
        Subscriber::create([
            'email' => $email,
            'ip_address' => $ip,
            'is_active' => true,
        ]);

        return back()->with('success', 'Thanks for subscribing! You have been added to our list.');
    }
}
