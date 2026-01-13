@props(['disabled' => false])

<input @disabled($disabled)
    {{ $attributes->merge(['class' => 'border-gray-100 focus:border-purple-500 focus:ring-purple-500/10 rounded-xl shadow-sm bg-gray-50/50']) }}>
