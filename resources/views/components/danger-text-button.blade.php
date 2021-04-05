<button {{ $attributes->merge(['type' => 'submit', 'class' => 'p-3 text-sm font-bold leading-5 text-pink-400 hover:text-red-600']) }}>
    {{ $slot }}
</button>
