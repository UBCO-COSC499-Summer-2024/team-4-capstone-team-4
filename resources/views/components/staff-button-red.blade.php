<button {{ $attributes->merge(['class' => 'inline-flex items-center px-3 py-1.5 text-[#ea3030] hover:text-white border border-[#ea3030] hover:bg-[#ea3030] focus:ring-1 focus:outline-none focus:ring-[#ea3030] font-medium rounded-lg text-sm text-center']) }}>
    {{ $slot }}
</button>
