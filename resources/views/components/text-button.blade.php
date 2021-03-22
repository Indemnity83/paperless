<button {{ $attributes->merge(['type' => 'submit', 'class' => 'p-3 text-sm font-bold leading-5 text-gray-400 hover:text-gray-600']) }}>
    {{ $slot }}
</button>
