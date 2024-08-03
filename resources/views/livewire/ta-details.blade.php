<div>
    <table class="min-w-full divide-y divide-gray-200 svcr-table" id="taTable">
        <x-coursedetails-ta-table-header :sortField="$sortField" :sortDirection="$sortDirection" />
        <tbody>
            @if($tas->count())
                @foreach ($tas as $ta)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $ta->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $ta->rating }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $ta->taCourses }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $ta->instructorName }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $ta->email }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="5" class="py-4 text-center text-gray-500">No TAs found.</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
