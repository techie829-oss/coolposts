<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cookie Policy') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 text-gray-900">
                    <!-- Page Header -->
                    <div class="text-center mb-12">
                        <h1 class="text-3xl font-bold text-gray-900 mb-4">Cookie Policy</h1>
                        <p class="text-lg text-gray-600">Last updated: January 09, 2026</p>
                    </div>

                    <!-- Content -->
                    <div class="prose max-w-none text-gray-600">
                        <p class="mb-6">CoolPosts uses cookies and similar technologies to enhance user experience.
                        </p>

                        <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">What Cookies Are Used For</h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                            <div class="bg-gray-50 p-6 rounded-lg text-center">
                                <div
                                    class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4">
                                        </path>
                                    </svg>
                                </div>
                                <h3 class="font-semibold text-gray-900 mb-2">Preferences</h3>
                                <p class="text-sm">Remembering your settings</p>
                            </div>
                            <div class="bg-gray-50 p-6 rounded-lg text-center">
                                <div
                                    class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                        </path>
                                    </svg>
                                </div>
                                <h3 class="font-semibold text-gray-900 mb-2">Analytics</h3>
                                <p class="text-sm">Understanding site usage</p>
                            </div>
                            <div class="bg-gray-50 p-6 rounded-lg text-center">
                                <div
                                    class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </div>
                                <h3 class="font-semibold text-gray-900 mb-2">Functionality</h3>
                                <p class="text-sm">Improving site features</p>
                            </div>
                        </div>

                        <p class="mb-4">Cookies do <strong>not</strong> collect personally identifiable information
                            unless explicitly provided by you.</p>

                        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r mt-6">
                            <p class="text-blue-700">By continuing to use CoolPosts, you consent to the use of cookies.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
