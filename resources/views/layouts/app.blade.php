<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google tag (gtag.js) -->


    <!-- Search Engine Verification -->
    <meta name="google-site-verification" content="f08c47fec0942fa0" />
    <meta name="yandex-verification" content="416734e950fff867" />

    <!-- PWA Meta Tags -->
    <meta name="theme-color" content="{{ $brandingSettings->primary_color ?? '#8b5cf6' }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="{{ $brandingSettings->brand_name ?? 'CoolPosts' }}">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="application-name" content="{{ $brandingSettings->brand_name ?? 'CoolPosts' }}">
    <meta name="msapplication-TileColor" content="{{ $brandingSettings->primary_color ?? '#8b5cf6' }}">
    <meta name="msapplication-tap-highlight" content="no">

    <!-- PWA Icons -->
    <!-- PWA Icons -->
    @if ($brandingSettings?->favicon)
        <link rel="icon" href="{{ asset('storage/' . $brandingSettings->favicon) }}">
        <link rel="apple-touch-icon" href="{{ asset('storage/' . $brandingSettings->favicon) }}">
    @else
        <link rel="icon" type="image/png" sizes="32x32" href="/icons/icon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/icons/icon-16x16.png">
        <link rel="apple-touch-icon" href="/icons/icon-192x192.png">
    @endif
    <link rel="manifest" href="/manifest.json">

    <title>{{ $title ?? config('app.name', 'Laravel') }}</title>

    {{ $head ?? '' }}

    <!-- Dynamic Branding Colors CSS -->
    <style>
        :root {
            --brand-primary:
                {{ $brandingSettings->primary_color ?? '#8b5cf6' }};
            --brand-secondary:
                {{ $brandingSettings->secondary_color ?? '#ec4899' }};
            --brand-accent:
                {{ $brandingSettings->accent_color ?? '#ef4444' }};
            --brand-gradient-start:
                {{ $brandingSettings->gradient_start ?? '#8b5cf6' }};
            --brand-gradient-end:
                {{ $brandingSettings->gradient_end ?? '#ec4899' }};
            --brand-gradient-third:
                {{ $brandingSettings->gradient_third ?? '#ef4444' }};
            --brand-gradient: linear-gradient(to right, var(--brand-gradient-start), var(--brand-gradient-end), var(--brand-gradient-third));
            --brand-gradient-hover: linear-gradient(to right, var(--brand-gradient-start), var(--brand-gradient-end), var(--brand-gradient-third));

            /* Purple variations */
            --purple-50: rgb(from var(--brand-primary) r g b / 0.1);
            --purple-100: rgb(from var(--brand-primary) r g b / 0.2);
            --purple-500: var(--brand-primary);
            --purple-600: color-mix(in srgb, var(--brand-primary) 90%, black);
            --purple-700: color-mix(in srgb, var(--brand-primary) 80%, black);

            /* Pink variations */
            --pink-50: rgb(from var(--brand-secondary) r g b / 0.1);
            --pink-100: rgb(from var(--brand-secondary) r g b / 0.2);
            --pink-500: var(--brand-secondary);
            --pink-600: color-mix(in srgb, var(--brand-secondary) 90%, black);
            --pink-700: color-mix(in srgb, var(--brand-secondary) 80%, black);

            /* Accent variations */
            --red-400: var(--brand-accent);
            --red-500: var(--brand-accent);
            --red-600: color-mix(in srgb, var(--brand-accent) 90%, black);
            --red-700: color-mix(in srgb, var(--brand-accent) 80%, black);
        }

        /* Gradient utilities */
        .bg-brand-gradient {
            background: var(--brand-gradient);
        }

        .text-brand-primary {
            color: var(--brand-primary);
        }

        .text-brand-secondary {
            color: var(--brand-secondary);
        }

        .text-brand-accent {
            color: var(--brand-accent);
        }

        .border-brand-primary {
            border-color: var(--brand-primary);
        }

        .bg-brand-primary\/10 {
            background-color: var(--purple-50);
        }

        .bg-brand-primary\/20 {
            background-color: var(--purple-100);
        }

        .hover\:bg-brand-primary\/10:hover {
            background-color: var(--purple-50);
        }

        .hover\:text-brand-primary:hover {
            color: var(--brand-primary);
        }

        /* Update existing purple and pink classes to use brand colors */
        .bg-purple-500,
        .bg-purple-600 {
            background-color: var(--brand-primary) !important;
        }

        .bg-purple-50,
        .hover\:bg-purple-50:hover {
            background-color: rgb(from var(--brand-primary) r g b / 0.1) !important;
        }

        .text-purple-600 {
            color: var(--brand-primary) !important;
        }

        .bg-pink-600 {
            background-color: var(--brand-secondary) !important;
        }

        /* Gradient button styles */
        .btn-gradient {
            background: var(--brand-gradient);
            transition: all 0.3s ease;
        }

        .btn-gradient:hover {
            filter: brightness(0.95);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        /* Updates to existing gradient classes */
        .from-purple-500 {
            --tw-gradient-from: var(--brand-gradient-start) !important;
        }

        .via-pink-500 {
            --tw-gradient-to: var(--brand-gradient-end) !important;
            --tw-gradient-stops: var(--tw-gradient-from), var(--brand-gradient-end) !important;
        }

        .to-red-500 {
            --tw-gradient-to: var(--brand-gradient-third) !important;
        }

        .from-purple-600 {
            --tw-gradient-from: color-mix(in srgb, var(--brand-gradient-start) 90%, black) !important;
        }

        .to-pink-600 {
            --tw-gradient-to: color-mix(in srgb, var(--brand-gradient-end) 90%, black) !important;
        }
    </style>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css?v=6.5.1" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- SEO: Noindex for Legal, Auth, and System Pages -->
    @php
        $noIndexRoutes = [
            'login',
            'register',
            'password.request',
            'password.reset',
            'verification.notice',
            'verification.verify',
        ];
    @endphp
    @if (in_array(Route::currentRouteName(), $noIndexRoutes) || request()->is('admin/*') || request()->is('user/*'))
        <meta name="robots" content="noindex, nofollow">
    @endif
    <!-- AdSense Removed primarily for 'Blog-First' Strategy -->


    <!-- PWA Scripts -->
    <script>
        // Register Service Worker
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/sw.js')
                    .then(function(registration) {
                        console.log('Service Worker registered successfully:', registration);
                    })
                    .catch(function(error) {
                        console.log('Service Worker registration failed:', error);
                    });
            });
        }

        // PWA Installation
        let deferredPrompt;
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;

            // Show install button if not already running in standalone mode
            if (!window.matchMedia('(display-mode: standalone)').matches) {
                console.log('App is not in standalone mode, showing install button');
                showInstallButton();
            } else {
                console.log('App is already in standalone mode');
            }
        });

        // Track installation
        window.addEventListener('appinstalled', (evt) => {
            localStorage.setItem('pwa-installed', 'true');
            hideInstallButton();

            // Track installation
            fetch('/pwa/install', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                        'content')
                }
            });
        });

        function showInstallButton() {
            // Create install button if it doesn't exist
            if (!document.getElementById('pwa-install-btn')) {
                const installBtn = document.createElement('button');
                installBtn.id = 'pwa-install-btn';
                installBtn.innerHTML = `
                    <div class="flex items-center gap-3">
                        <img src="/icons/icon-96x96.png" class="w-8 h-8 rounded-lg shadow-sm" alt="App Icon">
                        <span class="font-bold pr-1">Install App</span>
                    </div>
                `;
                installBtn.className =
                    'fixed bottom-6 right-6 bg-white/90 backdrop-blur-md border border-white/20 text-gray-900 px-4 py-3 rounded-2xl shadow-2xl z-50 hover:bg-white transition-all duration-300 hover:-translate-y-1 group border-purple-100 ring-1 ring-purple-100';
                installBtn.onclick = installPWA;
                document.body.appendChild(installBtn);
            }
        }

        function hideInstallButton() {
            const installBtn = document.getElementById('pwa-install-btn');
            if (installBtn) {
                installBtn.remove();
            }
        }

        function installPWA() {
            if (deferredPrompt) {
                deferredPrompt.prompt();
                deferredPrompt.userChoice.then((choiceResult) => {
                    if (choiceResult.outcome === 'accepted') {
                        console.log('User accepted the install prompt');
                    } else {
                        console.log('User dismissed the install prompt');
                    }
                    deferredPrompt = null;
                });
            }
        }

        // Offline detection
        window.addEventListener('online', function() {
            document.body.classList.remove('offline');
            showNotification('You are back online!', 'success');
        });

        window.addEventListener('offline', function() {
            document.body.classList.add('offline');
            showNotification('You are offline. Some features may be limited.', 'warning');
        });

        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 transition-all duration-300 ${type === 'success' ? 'bg-green-500 text-white' :
                type === 'warning' ? 'bg-yellow-500 text-white' :
                    'bg-blue-500 text-white'
                }`;
            notification.textContent = message;
            document.body.appendChild(notification);

            setTimeout(() => {
                notification.remove();
            }, 3000);
        }
    </script>
</head>

<body class="font-sans antialiased bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
    <!-- Animated Background Elements -->
    <div class="fixed inset-0 -z-10">
        <div
            class="absolute top-0 -left-4 w-72 h-72 bg-purple-200 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob">
        </div>
        <div
            class="absolute top-0 -right-4 w-72 h-72 bg-yellow-200 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob animation-delay-2000">
        </div>
        <div
            class="absolute -bottom-8 left-20 w-72 h-72 bg-pink-200 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob animation-delay-4000">
        </div>
    </div>

    <!-- Floating Particles -->
    <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none">
        <div class="absolute top-1/4 left-1/4 w-2 h-2 bg-purple-300 rounded-full opacity-40 animate-ping"></div>
        <div class="absolute top-3/4 right-1/4 w-1 h-1 bg-blue-300 rounded-full opacity-60 animate-pulse"></div>
        <div class="absolute top-1/2 left-3/4 w-1.5 h-1.5 bg-indigo-300 rounded-full opacity-50 animate-bounce"></div>
    </div>

    <!-- Navigation -->
    <nav class="bg-white/80 backdrop-blur-xl border-b border-white/20 sticky top-0 z-50" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Mobile Menu Button (Left) -->
                <div class="flex items-center md:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen"
                        class="p-2 text-gray-600 hover:text-indigo-600 focus:outline-none -ml-2">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>

                <!-- Logo (Center on Mobile, Left on Desktop) -->
                <a href="{{ route('welcome') }}" class="flex items-center space-x-3 group">
                    @if ($brandingSettings?->brand_logo)
                        <div
                            class="w-10 h-10 rounded-xl overflow-hidden shadow-lg group-hover:rotate-6 transition-transform">
                            <img src="{{ asset('storage/' . $brandingSettings->brand_logo) }}"
                                alt="{{ $brandingSettings->brand_name }}" class="w-full h-full object-cover">
                        </div>
                    @else
                        <div
                            class="w-10 h-10 rounded-xl overflow-hidden shadow-lg group-hover:rotate-6 transition-transform">
                            <img src="/icons/icon-192x192.png" alt="CoolPosts" class="w-full h-full object-cover">
                        </div>
                    @endif
                    <div>
                        <div class="text-xl font-black text-gray-900 tracking-tighter leading-none">
                            {{ $brandingSettings?->brand_name ?? 'CoolPosts' }}
                        </div>
                        <div
                            class="hidden md:block text-[9px] text-gray-400 font-bold uppercase tracking-[0.2em] mt-1.5">
                            {{ $brandingSettings?->brand_tagline ?? 'Blogger First' }}
                        </div>
                    </div>
                </a>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-1">
                    @auth
                        <a href="{{ route('dashboard') }}"
                            class="px-4 py-2 rounded-xl text-sm font-medium text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 transition-all duration-200 {{ request()->routeIs('dashboard') ? 'text-indigo-600 font-bold bg-indigo-50/50' : '' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('blog.index') }}"
                            class="px-4 py-2 rounded-xl text-sm font-medium text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 transition-all duration-200 {{ request()->routeIs('blog.*') ? 'text-indigo-600 font-bold bg-indigo-50/50' : '' }}">
                            Journal
                        </a>
                        @if (Auth::user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}"
                                class="px-4 py-2 rounded-xl text-sm font-medium text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 transition-all duration-200 {{ request()->routeIs('admin.*') ? 'text-indigo-600 font-bold bg-indigo-50/50' : '' }}">
                                Admin
                            </a>
                        @endif
                    @else
                        <a href="{{ route('welcome') }}"
                            class="px-4 py-2 rounded-xl text-sm font-medium text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 transition-all duration-200 {{ request()->routeIs('welcome') ? 'text-indigo-600 font-bold bg-indigo-50/50' : '' }}">
                            Home
                        </a>
                        <a href="{{ route('blog.index') }}"
                            class="px-4 py-2 rounded-xl text-sm font-medium text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 transition-all duration-200 {{ request()->routeIs('blog.*') ? 'text-indigo-600 font-bold bg-indigo-50/50' : '' }}">
                            Blog
                        </a>
                        <a href="{{ route('blog.how-we-work') }}"
                            class="px-4 py-2 rounded-xl text-sm font-medium text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 transition-all duration-200 {{ request()->routeIs('blog.how-we-work') ? 'text-indigo-600 font-bold bg-indigo-50/50' : '' }}">
                            How We Work
                        </a>
                    @endauth
                </div>

                <!-- Actions -->
                <div class="flex items-center space-x-4">
                    @auth
                        <!-- Productivity Write Button -->
                        <a href="{{ route('blog.create') }}"
                            class="hidden md:flex items-center px-6 py-2.5 rounded-xl text-sm font-black text-white bg-indigo-600 hover:bg-indigo-700 shadow-xl shadow-indigo-600/30 transition-all duration-300 hover:-translate-y-0.5 active:scale-95 ring-2 ring-indigo-500/20 group">
                            <i class="fas fa-pen-nib mr-2 text-indigo-200 group-hover:rotate-12 transition-transform"></i>
                            Write
                        </a>

                        <!-- Profile Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open"
                                class="flex items-center space-x-3 p-2 rounded-xl hover:bg-gray-100 transition-all duration-200 focus:outline-none ring-1 ring-gray-100 shadow-sm bg-white">
                                <div
                                    class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                                    <span
                                        class="text-white text-sm font-bold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                </div>
                                <i class="fas fa-chevron-down text-[10px] text-gray-400"></i>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="open" @click.away="open = false"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="absolute right-0 mt-3 w-56 bg-white rounded-2xl shadow-2xl border border-gray-100 py-2 z-50 overflow-hidden">

                                <div class="px-4 py-3 bg-gray-50/50 border-b border-gray-100 mb-2">
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest leading-none mb-1">
                                        Signed in as</p>
                                    <p class="text-sm font-black text-gray-900 truncate">{{ Auth::user()->name }}</p>
                                </div>

                                <a href="{{ route('profile.edit') }}"
                                    class="flex items-center px-4 py-2.5 text-sm text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition-all duration-200">
                                    <i class="fas fa-user-circle mr-3 opacity-50"></i>
                                    Account Settings
                                </a>

                                <a href="{{ route('dashboard') }}"
                                    class="flex items-center px-4 py-2.5 text-sm text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition-all duration-200">
                                    <i class="fas fa-chart-pie mr-3 opacity-50"></i>
                                    Performance
                                </a>

                                @if (Auth::user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}"
                                        class="flex items-center px-4 py-2.5 text-sm text-indigo-600 font-bold hover:bg-indigo-50 transition-all duration-200">
                                        <i class="fas fa-crown mr-3 text-indigo-400"></i>
                                        Admin Panel
                                    </a>
                                @endif

                                <div class="h-px bg-gray-100 my-2 mx-4"></div>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full flex items-center px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-all duration-200 font-medium">
                                        <i class="fas fa-sign-out-alt mr-3 opacity-70"></i>
                                        Sign Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <!-- Conversion "Get Started" Button -->
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('login') }}"
                                class="hidden sm:block text-sm font-bold text-gray-400 hover:text-indigo-600 transition-colors">Login</a>
                            <a href="{{ route('register') }}"
                                class="px-3 py-2 sm:px-8 text-sm sm:text-[15px] rounded-lg sm:rounded-xl font-bold sm:font-black text-indigo-600 sm:text-white bg-indigo-50 sm:bg-indigo-600 hover:bg-indigo-100 sm:hover:bg-indigo-700 shadow-none sm:shadow-2xl sm:shadow-indigo-600/30 transition-all duration-300 sm:hover:-translate-y-0.5 active:scale-95">
                                <span class="hidden sm:inline">Start Writing Free</span>
                                <i class="fas fa-arrow-right sm:hidden"></i>
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" x-transition class="md:hidden border-t border-gray-200 bg-white">
            <div class="px-2 pt-2 pb-3 space-y-1">
                @auth
                    <a href="{{ route('dashboard') }}"
                        class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-colors duration-200">
                        <i class="fas fa-chart-line mr-2"></i>
                        Dashboard
                    </a>
                    @if ($globalSettings->isLinkCreationEnabled())
                        <a href="{{ route('links.index') }}"
                            class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-colors duration-200">
                            <i class="fas fa-link mr-2"></i>
                            My Links
                        </a>
                    @endif
                    <a href="{{ route('blog.index') }}"
                        class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-colors duration-200">
                        <i class="fas fa-newspaper mr-2"></i>
                        Blog
                    </a>
                    @if ($globalSettings->isEarningsEnabled())
                        <a href="{{ route('earnings.index') }}"
                            class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-colors duration-200">
                            <i class="fas fa-dollar-sign mr-2"></i>
                            Earnings
                        </a>
                    @endif
                    @if ($globalSettings->isSubscriptionEnabled())
                        <a href="{{ route('subscriptions.plans') }}"
                            class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-colors duration-200">
                            <i class="fas fa-crown mr-2"></i>
                            Subscriptions
                        </a>
                    @endif
                    @if ($globalSettings->isReferralsEnabled())
                        <a href="{{ route('referrals.dashboard') }}"
                            class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-colors duration-200">
                            <i class="fas fa-users mr-2"></i>
                            Referrals
                        </a>
                    @endif
                    @if (Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}"
                            class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-colors duration-200">
                            <i class="fas fa-crown mr-2"></i>
                            Admin
                        </a>
                    @endif
                    <a href="{{ route('profile.edit') }}"
                        class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-colors duration-200">
                        <i class="fas fa-user mr-2"></i>
                        Profile
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="block">
                        @csrf
                        <button type="submit"
                            class="w-full text-left px-3 py-2 text-base font-medium text-red-600 hover:bg-red-50 rounded-lg transition-colors duration-200">
                            <i class="fas fa-sign-out-alt mr-2"></i>
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('welcome') }}"
                        class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-colors duration-200">
                        <i class="fas fa-home mr-2"></i>
                        Home
                    </a>
                    <a href="{{ route('blog.index') }}"
                        class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-colors duration-200">
                        <i class="fas fa-newspaper mr-2"></i>
                        Blog
                    </a>
                    <a href="{{ route('blog.how-we-work') }}"
                        class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-colors duration-200">
                        <i class="fas fa-cogs mr-2"></i>
                        How We Work
                    </a>
                    <a href="{{ route('login') }}"
                        class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-colors duration-200">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Login
                    </a>
                    <a href="{{ route('register') }}"
                        class="block px-3 py-2 text-base font-medium bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-lg transition-all duration-200">
                        <i class="fas fa-user-plus mr-2"></i>
                        Get Started
                    </a>
                @endauth
            </div>
        </div>
        </div>
    </nav>

    <!-- Page Heading -->
    @if (isset($header))
        <header class="bg-white/80 backdrop-blur-sm shadow-sm border-b border-gray-100/50 sticky top-16 z-40">
            <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
    @endif

    <!-- Page Content -->
    <main class="pt-0 pb-5">
        @if (session('success'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                    role="alert">
                    <strong class="font-bold">Success!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Error!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            </div>
        @endif
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Main Footer Content -->
            <div class="py-12">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8">
                    <!-- Company Info -->
                    <div class="lg:col-span-2">
                        <div class="flex items-center space-x-3 mb-4">
                            @if ($brandingSettings?->brand_logo)
                                <div class="w-10 h-10 rounded-xl overflow-hidden shadow-lg">
                                    <img src="{{ asset('storage/' . $brandingSettings->brand_logo) }}"
                                        alt="{{ $brandingSettings->brand_name }}" class="w-full h-full object-cover">
                                </div>
                            @else
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center shadow-lg"
                                    style="background: linear-gradient(to right, {{ $brandingSettings?->gradient_start ?? '#8b5cf6' }}, {{ $brandingSettings?->gradient_end ?? '#ec4899' }}, {{ $brandingSettings?->gradient_third ?? '#ef4444' }});">
                                    <i class="fas fa-link text-white text-lg"></i>
                                </div>
                            @endif
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">
                                    {{ $brandingSettings?->brand_name ?? config('app.name') }}
                                </h3>
                                <p class="text-sm font-medium text-indigo-600 italic">
                                    "Built for modern creators and independent publishers."
                                </p>
                                <p class="text-xs text-gray-400 mt-2">
                                    {{ $brandingSettings?->brand_tagline ?? 'Link Monetization Platform' }}
                                </p>
                            </div>
                        </div>
                        <p class="text-gray-600 text-sm leading-relaxed mb-6 max-w-md">
                            {{ $brandingSettings?->brand_description ?? 'CoolPosts is an AI blogging and content creation platform that helps users create, publish, and grow content.' }}
                        </p>

                        <!-- Social Links -->
                        <div class="flex space-x-3">
                            <a href="#"
                                class="w-8 h-8 bg-gray-100 hover:bg-purple-100 rounded-lg flex items-center justify-center transition-all duration-200 hover:scale-105">
                                <i class="fab fa-twitter text-gray-600 hover:text-purple-600 text-sm"></i>
                            </a>
                            <a href="#"
                                class="w-8 h-8 bg-gray-100 hover:bg-purple-100 rounded-lg flex items-center justify-center transition-all duration-200 hover:scale-105">
                                <i class="fab fa-facebook text-gray-600 hover:text-purple-600 text-sm"></i>
                            </a>
                            <a href="#"
                                class="w-8 h-8 bg-gray-100 hover:bg-purple-100 rounded-lg flex items-center justify-center transition-all duration-200 hover:scale-105">
                                <i class="fab fa-linkedin text-gray-600 hover:text-purple-600 text-sm"></i>
                            </a>
                            <a href="#"
                                class="w-8 h-8 bg-gray-100 hover:bg-purple-100 rounded-lg flex items-center justify-center transition-all duration-200 hover:scale-105">
                                <i class="fab fa-instagram text-gray-600 hover:text-purple-600 text-sm"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Company -->
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900 mb-4 uppercase tracking-wide">Company</h4>
                        <ul class="space-y-2">
                            <li><a href="{{ route('legal.about') }}"
                                    class="text-sm text-gray-600 hover:text-purple-600 transition-colors duration-200">About
                                    Us</a></li>
                            <li><a href="{{ route('blog.how-we-work') }}"
                                    class="text-sm text-gray-600 hover:text-purple-600 transition-colors duration-200">How
                                    It Works</a></li>
                            <li><a href="{{ route('subscriptions.plans') }}"
                                    class="text-sm text-gray-600 hover:text-purple-600 transition-colors duration-200">Pricing</a>
                            </li>
                            <li><a href="{{ route('blog.index') }}"
                                    class="text-sm text-gray-600 hover:text-purple-600 transition-colors duration-200">Blog</a>
                            </li>
                        </ul>
                    </div>

                    <!-- Resources -->
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900 mb-4 uppercase tracking-wide">Resources</h4>
                        <ul class="space-y-2">
                            <li><a href="{{ route('legal.contact') }}"
                                    class="text-sm text-gray-600 hover:text-purple-600 transition-colors duration-200">Contact
                                    Us</a></li>
                            @auth
                                <li><a href="{{ route('dashboard') }}"
                                        class="text-sm text-gray-600 hover:text-purple-600 transition-colors duration-200">Dashboard</a>
                                </li>
                            @endauth
                            <li><a href="{{ route('blog.templates') }}"
                                    class="text-sm text-gray-600 hover:text-purple-600 transition-colors duration-200">Blog
                                    Templates</a></li>
                            <li><a href="{{ route('legal.help') }}"
                                    class="text-sm text-gray-600 hover:text-purple-600 transition-colors duration-200">Help
                                    Center</a></li>
                            <li><a href="{{ route('legal.faq') }}"
                                    class="text-sm text-gray-600 hover:text-purple-600 transition-colors duration-200">FAQ</a>
                            </li>
                        </ul>
                    </div>

                    <!-- Legal -->
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900 mb-4 uppercase tracking-wide">Legal</h4>
                        <ul class="space-y-2">
                            <li><a href="{{ route('legal.terms') }}" target="_blank" rel="noopener noreferrer"
                                    class="text-sm text-gray-600 hover:text-purple-600 transition-colors duration-200">Terms
                                    of Service</a>
                            </li>
                            <li><a href="{{ route('legal.privacy') }}" target="_blank" rel="noopener noreferrer"
                                    class="text-sm text-gray-600 hover:text-purple-600 transition-colors duration-200">Privacy
                                    Policy</a>
                            </li>
                            <li><a href="{{ route('legal.cookies') }}" target="_blank" rel="noopener noreferrer"
                                    class="text-sm text-gray-600 hover:text-purple-600 transition-colors duration-200">Cookie
                                    Policy</a>
                            </li>
                            <li><a href="{{ route('legal.dmca') }}" target="_blank" rel="noopener noreferrer"
                                    class="text-sm text-gray-600 hover:text-purple-600 transition-colors duration-200">DMCA</a>
                            </li>
                            <li><a href="{{ route('legal.contact') }}" target="_blank" rel="noopener noreferrer"
                                    class="text-sm text-gray-600 hover:text-purple-600 transition-colors duration-200">Contact</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Newsletter Section -->
            <div class="py-8 border-t border-gray-200">
                <div class="text-center max-w-md mx-auto">
                    <h4 class="text-lg font-semibold text-gray-900 mb-2">Stay Updated</h4>
                    <p class="text-gray-600 text-sm mb-4">Get the latest updates on new features and tips delivered to
                        your inbox.</p>
                    <div class="flex flex-col sm:flex-row gap-2 w-full">
                        <form action="{{ route('newsletter.subscribe') }}" method="POST"
                            class="flex flex-col sm:flex-row gap-2 w-full">
                            @csrf
                            <input type="email" name="email" placeholder="Enter your email" required
                                class="flex-1 px-4 py-3 bg-gray-800 border border-gray-700 rounded-xl text-sm text-gray-200 placeholder-gray-500 focus:outline-none focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 transition-all min-w-0">
                            <button type="submit"
                                class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 whitespace-nowrap flex-shrink-0">
                                Subscribe
                            </button>
                        </form>
                    </div>
                    @if (session('newsletter_success'))
                        <p class="text-green-600 text-sm mt-2">{{ session('newsletter_success') }}</p>
                    @endif
                    @error('email')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Copyright -->
            <div class="py-6 border-t border-gray-200">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <p class="text-sm text-gray-500">
                        © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </footer>

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
    <!-- Google tag (gtag.js) - Deferred -->
    @if (app()->environment('production') && !request()->is('admin*'))
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
    @stack('scripts')
</body>

</html>
