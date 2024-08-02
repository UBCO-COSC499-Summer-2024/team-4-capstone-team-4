@props(['userRole'])

@if(in_array($userRole, ['dept_head', 'dept_staff', 'admin']))
    <button type="button" class="edit-sei-button" onclick="openSeiModal()">Edit</button>
@elseif($userRole === 'instructor')
    <button type="button" class="edit-sei-button" disabled>Edit</button>
@endif

<script>
    function openSeiModal() {
        document.getElementById('seiEditModal').classList.remove('hidden');
    }
</script>
