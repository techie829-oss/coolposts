<button
    {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-gray-900 border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest hover:bg-gray-800 focus:bg-gray-800 active:bg-gray-950 focus:outline-none focus:ring-4 focus:ring-purple-500/20 focus:ring-offset-2 transition ease-in-out duration-150 shadow-lg shadow-gray-200']) }}>
    {{ $slot }}
</button>
