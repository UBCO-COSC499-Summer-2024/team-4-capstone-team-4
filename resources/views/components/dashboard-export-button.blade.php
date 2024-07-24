<a href="{{ route('dept-report', ['dept_id' => $id]) }}" {{ $attributes->merge(['class' => 'inline-flex px-2 py-1 items-center text-[#3b4779] hover:text-white border border-[#3b4779] hover:bg-[#3b4779] focus:ring-1 focus:outline-none focus:ring-[#3b4779] font-medium rounded-lg text-xs text-center']) }}>
    {{ $slot }}
</a>