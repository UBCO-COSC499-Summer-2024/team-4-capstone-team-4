<button {{ $attributes->merge(['class' => 'inline-flex items-center px-3 py-1.5 text-[#3B784F] hover:text-white border border-[#3B784F] hover:bg-[#3B784F] focus:ring-1 focus:outline-none focus:ring-[#3B784F] font-medium rounded-lg text-sm text-center']) }}>
    {{ $slot }}
</button>
