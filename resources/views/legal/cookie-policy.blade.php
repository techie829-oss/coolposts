<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cookie Policy') }}
        </h2>
    </x-slot>
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">Cookie Policy</h1>
                    <p class="text-lg text-gray-600">How we use cookies and similar technologies</p>
                </div>

                <div class="prose max-w-none">
                    <p class="text-gray-600 mb-6">This Cookie Policy explains how {{ config('app.name') }} uses cookies and similar technologies to recognize you when you visit our website. It explains what these technologies are and why we use them, as well as your rights to control our use of them.</p>

                    <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">What are cookies?</h2>
                    <p class="text-gray-600 mb-6">Cookies are small data files that are placed on your computer or mobile device when you visit a website. Cookies are widely used by website owners in order to make their websites work, or to work more efficiently, as well as to provide reporting information.</p>

                    <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">How we use cookies</h2>
                    <p class="text-gray-600 mb-6">We use cookies for several reasons. Some cookies are required for technical reasons in order for our website to operate, and we refer to these as "essential" or "strictly necessary" cookies. Other cookies also enable us to track and target the interests of our users to enhance the experience on our website.</p>

                    <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Types of cookies we use</h2>

                    <div class="bg-gray-50 rounded-lg p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Essential Cookies</h3>
                        <p class="text-gray-600 mb-3">These cookies are essential to provide you with services available through our website and to enable you to use some of its features. Without these cookies, the services that you have asked for cannot be provided.</p>
                        <ul class="list-disc list-inside text-gray-600 space-y-1">
                            <li>Authentication cookies to keep you logged in</li>
                            <li>Security cookies to protect against fraud</li>
                            <li>Session cookies to maintain your session</li>
                        </ul>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Analytics Cookies</h3>
                        <p class="text-gray-600 mb-3">These cookies allow us to analyze how our website is being accessed and used, and enable us to track performance of our site.</p>
                        <ul class="list-disc list-inside text-gray-600 space-y-1">
                            <li>Google Analytics cookies to understand user behavior</li>
                            <li>Performance monitoring cookies</li>
                            <li>Error tracking cookies</li>
                        </ul>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Functionality Cookies</h3>
                        <p class="text-gray-600 mb-3">These cookies allow our website to remember choices you make when you use our website.</p>
                        <ul class="list-disc list-inside text-gray-600 space-y-1">
                            <li>Language preference cookies</li>
                            <li>Theme preference cookies</li>
                            <li>User interface customization cookies</li>
                        </ul>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Advertising Cookies</h3>
                        <p class="text-gray-600 mb-3">These cookies are used to make advertising messages more relevant to you and your interests.</p>
                        <ul class="list-disc list-inside text-gray-600 space-y-1">
                            <li>Ad targeting cookies</li>
                            <li>Ad performance cookies</li>
                            <li>Retargeting cookies</li>
                        </ul>
                    </div>

                    <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">How to control cookies</h2>
                    <p class="text-gray-600 mb-6">You have the right to decide whether to accept or reject cookies. You can exercise your cookie rights by setting your preferences in the Cookie Consent Manager.</p>

                    <div class="bg-blue-50 rounded-lg p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Browser Settings</h3>
                        <p class="text-gray-600 mb-3">You can also set or amend your web browser controls to accept or refuse cookies. If you choose to reject cookies, you may still use our website though your access to some functionality and areas of our website may be restricted.</p>
                    </div>

                    <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Updates to this policy</h2>
                    <p class="text-gray-600 mb-6">We may update this Cookie Policy from time to time in order to reflect, for example, changes to the cookies we use or for other operational, legal or regulatory reasons. Please therefore re-visit this Cookie Policy regularly to stay informed about our use of cookies and related technologies.</p>

                    <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Contact us</h2>
                    <p class="text-gray-600 mb-6">If you have any questions about our use of cookies or other technologies, please contact us at privacy@{{ config('app.domain') }}.</p>
                </div>

                <div class="mt-8 text-center">
                    <a href="{{ route('legal.contact') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        Contact Us
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
