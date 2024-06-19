@vite(['resources/css/staff.css'])
<table class="min-w-full divide-y divide gray-200">
    <thead class="bg=gray-50">
        <tr>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Column 1
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Column 2
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Column 3
            </th>
        </tr>
    </thead>
    <tbody class="bg-white-divide-y divide-gray-200">
        @foreach ($data as $item)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">
                    {{$item['Column 1']}}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    {{$item['Column 2']}}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    {{$item['Column 3']}}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>