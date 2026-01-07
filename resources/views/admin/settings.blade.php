<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Technical Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Header -->
                    <div class="mb-8">
                        <h1 class="text-3xl font-bold text-gray-900">Technical Settings</h1>
                        <p class="mt-2 text-gray-600">Configure security, notifications, and platform technical settings.</p>
                    </div>

                    <!-- Page Description -->
                    <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle text-blue-400"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">Technical Settings</h3>
                                <p class="text-sm text-blue-700 mt-1">
                                    Configure security settings, rate limiting, notifications, and platform technical configurations.
                                    For business settings like monetization rates and subscriptions, use <a href="{{ route('global-settings') }}" class="underline">Business Settings</a>.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Settings Tabs -->
                    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                        <div class="border-b border-gray-200">
                            <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                                <button class="border-blue-500 text-blue-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm tab-button active" data-tab="general">
                                    General Settings
                                </button>

                                <button class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm tab-button" data-tab="security">
                                    Security
                                </button>
                                <button class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm tab-button" data-tab="notifications">
                                    Notifications
                                </button>
                            </nav>
                        </div>

                        <!-- General Settings Tab -->
                        <div id="general" class="tab-content active p-6">
                            <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
                                @csrf
                                @method('PATCH')

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Platform Information -->
                                    <div class="space-y-4">
                                        <h3 class="text-lg font-medium text-gray-900">Platform Information</h3>

                                        <div>
                                            <label for="platform_name" class="block text-sm font-medium text-gray-700">Platform Name</label>
                                            <input type="text" name="platform_name" id="platform_name" value="{{ $settings->platform_name ?? 'CoolPosts' }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                        </div>

                                        <div>
                                            <label for="platform_description" class="block text-sm font-medium text-gray-700">Platform Description</label>
                                            <textarea name="platform_description" id="platform_description" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ $settings->platform_description ?? '' }}</textarea>
                                        </div>

                                        <div>
                                            <label for="default_currency" class="block text-sm font-medium text-gray-700">Default Currency</label>
                                            <select name="default_currency" id="default_currency" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                                <option value="INR" {{ ($settings->default_currency ?? 'INR') == 'INR' ? 'selected' : '' }}>INR (Indian Rupee)</option>
                                                <option value="USD" {{ ($settings->default_currency ?? 'INR') == 'USD' ? 'selected' : '' }}>USD (US Dollar)</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- System Settings -->
                                    <div class="space-y-4">
                                        <h3 class="text-lg font-medium text-gray-900">System Settings</h3>

                                        <div>
                                            <label for="maintenance_mode" class="flex items-center">
                                                <input type="checkbox" name="maintenance_mode" id="maintenance_mode" value="1" {{ ($settings->maintenance_mode ?? false) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                                <span class="ml-2 text-sm text-gray-700">Maintenance Mode</span>
                                            </label>
                                        </div>

                                        <div>
                                            <label for="registration_enabled" class="flex items-center">
                                                <input type="checkbox" name="registration_enabled" id="registration_enabled" value="1" {{ ($settings->registration_enabled ?? true) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                                <span class="ml-2 text-sm text-gray-700">Allow New Registrations</span>
                                            </label>
                                        </div>

                                        <div>
                                            <label for="email_verification_required" class="flex items-center">
                                                <input type="checkbox" name="email_verification_required" id="email_verification_required" value="1" {{ ($settings->email_verification_required ?? false) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                                <span class="ml-2 text-sm text-gray-700">Require Email Verification</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex justify-end">
                                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                        Save General Settings
                                    </button>
                                </div>
                            </form>
                        </div>



                        <!-- Security Tab -->
                        <div id="security" class="tab-content hidden p-6">
                            <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
                                @csrf
                                @method('PATCH')

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Rate Limiting -->
                                    <div class="space-y-4">
                                        <h3 class="text-lg font-medium text-gray-900">Rate Limiting</h3>

                                        <div>
                                            <label for="auth_rate_limit" class="block text-sm font-medium text-gray-700">Auth Attempts Limit</label>
                                            <input type="number" name="auth_rate_limit" id="auth_rate_limit" value="{{ $settings->auth_rate_limit ?? 5 }}" min="1" max="20" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                        </div>

                                        <div>
                                            <label for="api_rate_limit" class="block text-sm font-medium text-gray-700">API Rate Limit</label>
                                            <input type="number" name="api_rate_limit" id="api_rate_limit" value="{{ $settings->api_rate_limit ?? 100 }}" min="10" max="1000" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                    </div>

                                    <!-- Security Features -->
                                    <div class="space-y-4">
                                        <h3 class="text-lg font-medium text-gray-900">Security Features</h3>

                                        <div>
                                            <label for="two_factor_required" class="flex items-center">
                                                <input type="checkbox" name="two_factor_required" id="two_factor_required" value="1" {{ ($settings->two_factor_required ?? false) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                                <span class="ml-2 text-sm text-gray-700">Require 2FA for Admins</span>
                                            </label>
                                        </div>

                                        <div>
                                            <label for="session_timeout" class="block text-sm font-medium text-gray-700">Session Timeout (minutes)</label>
                                            <input type="number" name="session_timeout" id="session_timeout" value="{{ $settings->session_timeout ?? 120 }}" min="15" max="1440" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                    </div>
                                </div>

                                <div class="flex justify-end">
                                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                        Save Security Settings
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Notifications Tab -->
                        <div id="notifications" class="tab-content hidden p-6">
                            <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
                                @csrf
                                @method('PATCH')

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Email Notifications -->
                                    <div class="space-y-4">
                                        <h3 class="text-lg font-medium text-gray-900">Email Notifications</h3>

                                        <div>
                                            <label for="email_notifications_enabled" class="flex items-center">
                                                <input type="checkbox" name="email_notifications_enabled" id="email_notifications_enabled" value="1" {{ ($settings->email_notifications_enabled ?? true) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                                <span class="ml-2 text-sm text-gray-700">Enable Email Notifications</span>
                                            </label>
                                        </div>

                                        <div>
                                            <label for="admin_email" class="block text-sm font-medium text-gray-700">Admin Email</label>
                                            <input type="email" name="admin_email" id="admin_email" value="{{ $settings->admin_email ?? '' }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                    </div>

                                    <!-- System Notifications -->
                                    <div class="space-y-4">
                                        <h3 class="text-lg font-medium text-gray-900">System Notifications</h3>

                                        <div>
                                            <label for="system_notifications_enabled" class="flex items-center">
                                                <input type="checkbox" name="system_notifications_enabled" id="system_notifications_enabled" value="1" {{ ($settings->system_notifications_enabled ?? true) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                                <span class="ml-2 text-sm text-gray-700">Enable System Notifications</span>
                                            </label>
                                        </div>

                                        <div>
                                            <label for="notification_frequency" class="block text-sm font-medium text-gray-700">Notification Frequency</label>
                                            <select name="notification_frequency" id="notification_frequency" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                                <option value="immediate" {{ ($settings->notification_frequency ?? 'immediate') == 'immediate' ? 'selected' : '' }}>Immediate</option>
                                                <option value="hourly" {{ ($settings->notification_frequency ?? 'immediate') == 'hourly' ? 'selected' : '' }}>Hourly</option>
                                                <option value="daily" {{ ($settings->notification_frequency ?? 'immediate') == 'daily' ? 'selected' : '' }}>Daily</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex justify-end">
                                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                        Save Notification Settings
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabButtons = document.querySelectorAll('.tab-button');
        const tabContents = document.querySelectorAll('.tab-content');

        tabButtons.forEach(button => {
            button.addEventListener('click', function() {
                const targetTab = this.getAttribute('data-tab');

                // Remove active class from all buttons and contents
                tabButtons.forEach(btn => btn.classList.remove('active', 'border-blue-500', 'text-blue-600'));
                tabButtons.forEach(btn => btn.classList.add('border-transparent', 'text-gray-500'));
                tabContents.forEach(content => content.classList.remove('active'));
                tabContents.forEach(content => content.classList.add('hidden'));

                // Add active class to clicked button and target content
                this.classList.add('active', 'border-blue-500', 'text-blue-600');
                this.classList.remove('border-transparent', 'text-gray-500');
                document.getElementById(targetTab).classList.add('active');
                document.getElementById(targetTab).classList.remove('hidden');
            });
        });
    });
    </script>
</x-app-layout>
