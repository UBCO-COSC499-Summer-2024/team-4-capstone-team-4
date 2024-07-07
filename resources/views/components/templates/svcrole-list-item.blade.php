@props(['svcrole'])
<tr class="svcr-list-item" id="svcr-{{ $svcrole->id }}">
{{-- checkbox --}}
    <td class="svcr-list-item-cell" data-column="select">
        <input type="checkbox" class="svcr-list-item-select" id="svcr-select-{{ $svcrole->id }}" value="{{ $svcrole->id }}">
    </td>
    <td class="svcr-list-item-cell" data-column="role" data-original="{{ $svcrole->name }}">
        <span class="svcr-list-item-title">{{ $svcrole->name }}</span>
    </td>
    <td class="svcr-list-item-cell" data-column="area" data-original="{{ $svcrole->area }}">
        <span class="svcr-list-item-title">{{ $svcrole->area->name }}</span>
    </td>
    <td class="svcr-list-item-cell" data-column="description" data-original="{{ $svcrole->description }}">
        <span class="svcr-list-item-title">{{ $svcrole->description }}</span>
    </td>
    <td class="svcr-list-item-cell" data-column="instructors" data-original="{{ $svcrole->instructors }}">
        <span class="svcr-list-item-title">{{ $svcrole->instructors }}</span>
    </td>
    <td class="svcr-list-item-cell" data-column="manage">
        <div class="svcr-list-item-actions">
            <button class="svcr-list-item-action">
                <span class="material-symbols-outlined icon">edit</span>
            </button>
            <button class="svcr-list-item-action">
                <span class="material-symbols-outlined icon">delete</span>
            </button>
        </div>
    </td>
</tr>