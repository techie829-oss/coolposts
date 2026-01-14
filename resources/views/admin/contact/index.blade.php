<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Contact Messages') }}
            </h2>
            <a href="{{ route('admin.dashboard') }}"
                class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Search and Filters -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('admin.contact.index') }}" class="flex flex-wrap gap-4">
                        <div class="flex-1 min-w-64">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Search by name, email, or subject..."
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div class="flex gap-2">
                            <select name="status"
                                class="w-full sm:w-auto min-w-[140px] px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 cursor-pointer">
                                <option value="">All Status</option>
                                <option value="unread" {{ request('status') === 'unread' ? 'selected' : '' }}>Unread
                                </option>
                                <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>Read
                                </option>
                            </select>
                            <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                                <i class="fas fa-search mr-2"></i>Search
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Messages Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if ($messages->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Sender</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Subject</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Message</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Date</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($messages as $message)
                                        <tr
                                            class="hover:bg-gray-50 {{ $message->is_read ? 'bg-gray-50' : 'bg-white font-medium' }}">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $message->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $message->email }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ ucfirst($message->subject) }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate cursor-help"
                                                title="{{ $message->message }}">
                                                {{ Str::limit($message->message, 50) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $message->created_at->format('M d, Y H:i') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if ($message->is_read)
                                                    <span
                                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                        Read
                                                    </span>
                                                @else
                                                    <span
                                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        New
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-3">
                                                    <button
                                                        onclick='viewMessage(@json($message->name), @json($message->email), @json($message->subject), @json($message->message))'
                                                        class="text-blue-600 hover:text-blue-900 transition-colors duration-200">
                                                        <i class="fas fa-eye mr-1"></i>View
                                                    </button>
                                                    <form method="POST"
                                                        action="{{ route('admin.contact.update-status', $message) }}"
                                                        class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        @if ($message->is_read)
                                                            <input type="hidden" name="is_read" value="0">
                                                            <button type="submit"
                                                                class="text-yellow-600 hover:text-yellow-900 transition-colors duration-200">
                                                                <i class="fas fa-envelope mr-1"></i>Mark Unread
                                                            </button>
                                                        @else
                                                            <input type="hidden" name="is_read" value="1">
                                                            <button type="submit"
                                                                class="text-green-600 hover:text-green-900 transition-colors duration-200">
                                                                <i class="fas fa-check mr-1"></i>Mark Read
                                                            </button>
                                                        @endif
                                                    </form>
                                                    <a href="mailto:{{ $message->email }}?subject=Re: {{ $message->subject }}"
                                                        class="text-purple-600 hover:text-purple-900 transition-colors duration-200">
                                                        <i class="fas fa-reply mr-1"></i>Reply
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $messages->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No messages found</h3>
                            <p class="text-gray-500">Your inbox is empty.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Message Modal -->
    <div id="messageModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-lg font-bold text-gray-900" id="modalSubject"></h3>
                    <button onclick="closeMessageModal()" class="text-gray-400 hover:text-gray-500">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="mb-4 text-sm text-gray-600 border-b pb-4">
                    <p><strong>From:</strong> <span id="modalName"></span> &lt;<span id="modalEmail"></span>&gt;</p>
                </div>

                <div class="mt-2 text-gray-700 whitespace-pre-wrap leading-relaxed" id="modalMessage"></div>

                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" onclick="closeMessageModal()"
                        class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">
                        Close
                    </button>
                    <a id="modalReplyBtn" href="#"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        <i class="fas fa-reply mr-2"></i>Reply via Email
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function viewMessage(name, email, subject, message) {
            document.getElementById('modalSubject').textContent = subject;
            document.getElementById('modalName').textContent = name;
            document.getElementById('modalEmail').textContent = email;
            document.getElementById('modalMessage').textContent = message;
            document.getElementById('modalReplyBtn').href = `mailto:${email}?subject=Re: ${subject}`;

            document.getElementById('messageModal').classList.remove('hidden');
        }

        function closeMessageModal() {
            document.getElementById('messageModal').classList.add('hidden');
        }
    </script>
</x-app-layout>
