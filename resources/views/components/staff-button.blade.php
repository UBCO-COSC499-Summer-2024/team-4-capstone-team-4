<button {{ $attributes->merge(['class' => 'inline-flex px-3 py-1.5 items-center text-[#3b4779] hover:text-white border border-[#3b4779] hover:bg-[#3b4779] focus:ring-1 focus:outline-none focus:ring-[#3b4779] font-medium rounded-lg text-sm text-center']) }}>
    {{ $slot }}
</button>
