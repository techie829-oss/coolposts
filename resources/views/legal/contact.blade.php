<x-app-layout>
    <x-slot name="title">Contact Us - CoolPosts | Support & Help</x-slot>

    <x-slot name="head">
        <meta name="description"
            content="Get in touch with CoolPosts support team. Contact us for technical support, billing questions, feature requests, or general inquiries. We're here to help!">
        <meta name="keywords"
            content="contact support, help desk, technical support, customer service, CoolPosts support, contact form">
        <meta name="robots" content="index, follow">
        <link rel="canonical" href="{{ url('/contact') }}">

        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url('/contact') }}">
        <meta property="og:title" content="Contact Us - CoolPosts | Support & Help">
        <meta property="og:description"
            content="Get in touch with CoolPosts support team. Contact us for technical support, billing questions, feature requests, or general inquiries. We're here to help!">
        <meta property="og:image" content="{{ asset('images/og-contact.jpg') }}">
        <meta property="og:site_name" content="CoolPosts">
        <meta property="og:locale" content="en_US">

        <!-- Twitter -->
        <meta property="twitter:card" content="summary_large_image">
        <meta property="twitter:url" content="{{ url('/contact') }}">
        <meta property="twitter:title" content="Contact Us - CoolPosts | Support & Help">
        <meta property="twitter:description"
            content="Get in touch with CoolPosts support team. Contact us for technical support, billing questions, feature requests, or general inquiries. We're here to help!">
        <meta property="twitter:image" content="{{ asset('images/og-contact.jpg') }}">
    </x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Contact Us') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div
                        class="relative overflow-hidden bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 rounded-2xl shadow-lg mb-10 px-8 py-12 text-center">
                        <div class="absolute inset-0 opacity-15">
                            <svg class="h-full w-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                                <path d="M0 0 L100 100 M100 0 L0 100" stroke="white" stroke-width="2" />
                            </svg>
                        </div>
                        <div class="relative z-10">
                            <h1 class="text-4xl font-bold text-white mb-2 drop-shadow-sm">Contact Us</h1>
                            <p class="text-lg text-indigo-50 font-medium">Get in touch with our support team</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Contact Form -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-4">Send us a Message</h2>

                            <form action="#" method="POST" class="space-y-4">
                                @csrf
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                                    <input type="text" name="name" id="name" required
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>

                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                    <input type="email" name="email" id="email" required
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>

                                <div>
                                    <label for="subject" class="block text-sm font-medium text-gray-700">Subject</label>
                                    <select name="subject" id="subject" required
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">Select a subject</option>
                                        <option value="general">General Inquiry</option>
                                        <option value="technical">Technical Support</option>
                                        <option value="billing">Billing & Payments</option>
                                        <option value="feature">Feature Request</option>
                                        <option value="bug">Bug Report</option>
                                        <option value="partnership">Partnership</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="message" class="block text-sm font-medium text-gray-700">Message</label>
                                    <textarea name="message" id="message" rows="4" required
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                                </div>

                                <div>
                                    <button type="submit"
                                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-200">
                                        Send Message
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Contact Information -->
                        <div class="space-y-6">
                            <div>
                                <h2 class="text-xl font-semibold text-gray-900 mb-4">Get in Touch</h2>
                                <p class="text-gray-600 mb-6">Our support team is here to help you with any questions or
                                    issues you may have.</p>
                            </div>

                            <div class="space-y-4">
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-900">Email Support</h3>
                                        <p class="text-sm text-gray-600">techie829@gmail.com</p>
                                        <p class="text-xs text-gray-500 mt-1">Response within 24 hours</p>
                                    </div>
                                </div>

                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-900">Response Time</h3>
                                        <p class="text-sm text-gray-600">Within 24 hours</p>
                                        <p class="text-xs text-gray-500 mt-1">Monday - Friday, 9 AM - 6 PM</p>
                                    </div>
                                </div>

                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-900">Live Chat</h3>
                                        <p class="text-sm text-gray-600">Available in your dashboard</p>
                                        <p class="text-xs text-gray-500 mt-1">Real-time support during business hours
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Quick Links -->
                            <div class="bg-blue-50 rounded-lg p-4">
                                <h3 class="text-sm font-medium text-gray-900 mb-3">Quick Links</h3>
                                <div class="space-y-2">
                                    <a href="{{ route('legal.faq') }}"
                                        class="block text-sm text-blue-600 hover:text-blue-800">Frequently Asked
                                        Questions</a>
                                    <a href="{{ route('legal.help') }}"
                                        class="block text-sm text-blue-600 hover:text-blue-800">Help Center</a>
                                    <a href="{{ route('api.docs') }}"
                                        class="block text-sm text-blue-600 hover:text-blue-800">API Documentation</a>
                                    <a href="{{ route('legal.terms') }}"
                                        class="block text-sm text-blue-600 hover:text-blue-800">Terms of Service</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- FAQ Preview -->
                    <div class="mt-12 bg-gray-50 rounded-lg p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Frequently Asked Questions</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-white rounded-lg p-4">
                                <h3 class="font-medium text-gray-900 mb-2">How do I create my first link?</h3>
                                <p class="text-sm text-gray-600">Sign up for an account, then click "Create Link" in
                                    your dashboard...</p>
                                <a href="{{ route('legal.faq') }}"
                                    class="text-sm text-blue-600 hover:text-blue-800">Read more →</a>
                            </div>
                            <div class="bg-white rounded-lg p-4">
                                <h3 class="font-medium text-gray-900 mb-2">How does monetization work?</h3>
                                <p class="text-sm text-gray-600">When someone clicks your monetized link, they'll see a
                                    brief advertisement...</p>
                                <a href="{{ route('legal.faq') }}"
                                    class="text-sm text-blue-600 hover:text-blue-800">Read more →</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>