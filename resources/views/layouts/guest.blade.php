<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google tag (gtag.js) -->
    @if (!request()->is('admin*'))
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-FBB3YFWDKF"></script>
        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }
            gtag('js', new Date());

            gtag('config', 'G-FBB3YFWDKF');
        </script>
    @endif

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="font-sans antialiased bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 min-h-screen flex flex-col relative">
    <!-- Animated Background Elements -->
    <div class="fixed inset-0 -z-10">
        <div
            class="absolute top-0 -left-4 w-72 h-72 bg-purple-300 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob">
        </div>
        <div
            class="absolute top-0 -right-4 w-72 h-72 bg-yellow-300 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob animation-delay-2000">
        </div>
        <div
            class="absolute -bottom-8 left-20 w-72 h-72 bg-pink-300 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob animation-delay-4000">
        </div>
    </div>

    <!-- Floating Particles -->
    <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none">
        <div class="absolute top-1/4 left-1/4 w-2 h-2 bg-white rounded-full opacity-60 animate-ping"></div>
        <div class="absolute top-3/4 right-1/4 w-1 h-1 bg-purple-300 rounded-full opacity-80 animate-pulse"></div>
        <div class="absolute top-1/2 left-3/4 w-1.5 h-1.5 bg-yellow-300 rounded-full opacity-70 animate-bounce"></div>
    </div>

    <!-- Main Content -->
    <div class="w-full flex-grow flex items-center justify-center p-5 relative z-10">
        <!-- Content Container - Full Width -->
        <div class="w-full flex items-center justify-center py-10">
            {{ $slot }}
        </div>
    </div>

    <!-- Footer -->
    <div class="text-center pb-8 mt-auto relative z-10 w-full">
        <div class="bg-white/10 backdrop-blur-md border border-white/10 rounded-full px-6 py-2 inline-block shadow-lg">
            <p class="text-sm text-gray-300 font-medium">
                © 2025 CoolPosts. All rights reserved.
            </p>
        </div>
    </div>

    <style>
        @keyframes blob {
            0% {
                transform: translate(0px, 0px) scale(1);
            }

            33% {
                transform: translate(30px, -50px) scale(1.1);
            }

            66% {
                transform: translate(-20px, 20px) scale(0.9);
            }

            100% {
                transform: translate(0px, 0px) scale(1);
            }
        }

        .animate-blob {
            animation: blob 7s infinite;
        }

        .animation-delay-2000 {
            animation-delay: 2s;
        }

        .animation-delay-4000 {
            animation-delay: 4s;
        }
    </style>
</body>

</html>
