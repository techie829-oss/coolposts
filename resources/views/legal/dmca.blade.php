<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('DMCA Policy') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 text-gray-900">
                    <!-- Page Header -->
                    <div class="text-center mb-12">
                        <h1 class="text-3xl font-bold text-gray-900 mb-4">DMCA Policy</h1>
                        <p class="text-lg text-gray-600">Last updated: January 09, 2026</p>
                    </div>

                    <!-- Content -->
                    <div class="prose max-w-none text-gray-600">
                        <p class="mb-6">CoolPosts respects intellectual property rights.</p>

                        <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Filing a DMCA Notice</h2>
                        <p class="mb-4">If you believe content on CoolPosts infringes your copyright, please send a
                            DMCA notice including:</p>

                        <div class="bg-gray-50 rounded-lg p-6 mb-6 border border-gray-100">
                            <ul class="list-disc pl-6 space-y-2">
                                <li>Your name and contact details</li>
                                <li>Description of the copyrighted work</li>
                                <li>URL of the allegedly infringing content</li>
                                <li>A statement of good-faith belief</li>
                                <li>A statement confirming accuracy under penalty of perjury</li>
                            </ul>
                        </div>

                        <p class="mb-4">Valid requests will be reviewed and acted upon promptly.</p>
                    </div>

                    <!-- Contact Box -->
                    <div class="mt-12 bg-blue-50 rounded-xl p-8 text-center">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Submit a Notice</h3>
                        <p class="text-gray-600 mb-6">Please send your DMCA notice to our designated agent.</p>

                        <div class="inline-block text-left bg-white p-6 rounded-lg shadow-sm">
                            <p class="mb-2">📧 <strong>Email:</strong> <a href="mailto:support@coolposts.site"
                                    class="text-blue-600 hover:text-blue-800">support@coolposts.site</a></p>
                            <p>📌 <strong>Subject:</strong> DMCA Notice</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
