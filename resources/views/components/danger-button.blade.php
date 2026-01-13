<button
    {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-4 focus:ring-red-500/20 focus:ring-offset-2 transition ease-in-out duration-150 shadow-lg shadow-red-100']) }}>
    {{ $slot }}
</button>
