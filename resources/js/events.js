Livewire.on('help-search-results', function (results) {
    console.log('Search results updated:', results);
    Livewire.dispatch('search-results-updated', results);
});

Livewire.on('show-toast', (data) => {
    // it seems data is an array of objects
    console.log(data);
    data.forEach((toast) => {
        console.log(toast)
        Toastify({
            text: toast.message,
            selector: document.querySelector('main > .container'),
            newWindow: toast.destination ? true : false,
            duration: toast.duration ?? 5000,
            close: toast.close ?? true,
            gravity: toast.gravity ?? "bottom",
            position: toast.position ?? "right",
            className: toast.className ?? toast.class?? `toastify-${toast.type}`, // Optional styling
            stopOnFocus: toast.stopOnFocus ??  true,
            destination: toast.destination ?? null,
            ariaLive: toast.ariaLive ?? toast.aria ?? "polite", // `assertive` or `off`
        }).showToast();
    });
});

Livewire.on('confirmDelete', (data) => {
    data.forEach((item) => {
        if (confirm(item.message)) {
            if (item.model) {
                switch(item.model) {
                    case 'svcr_item_delete':
                        Livewire.dispatch('svcr-item-delete', { id: item.id });
                        break;
                    case 'sr_manage_delete':
                        Livewire.dispatch('svcr-manage-delete', { id: item.id });
                        break;
                    case 'staff':
                        Livewire.dispatch('deleteStaff', { id: item.id });
                        break;
                    case 'area':
                        Livewire.dispatch('deleteArea', { id: item.id });
                        break;
                    case 'role':
                        Livewire.dispatch('deleteRole', { id: item.id });
                        break;
                    case 'user':
                        Livewire.dispatch('deleteUser', { id: item.id });
                        break;
                    case 'sr_role_assignment':
                        Livewire.dispatch('sr-remove-instructor', { id: item.id });
                        break;
                    default:
                        console.log('Model not found');
                }
            } else {
                Livewire.dispatch('deleteServiceRole', { id: item.serviceRoleId});
            }
        }
    })
});

Livewire.on('confirmArchive', (data) => {
    data.forEach((item) => {
        if (confirm(item.message)) {
            switch(item.model) {
                case 'svcr_item_archive':
                    Livewire.dispatch('svcr-item-archive', { id: item.id });
                    break;
                case 'svcr_item_unarchive':
                    Livewire.dispatch('svcr-item-unarchive', { id: item.id });
                    break;
                case 'sr_manage_archive':
                    Livewire.dispatch('svcr-manage-archive', { id: item.id });
                    break;
                case 'sr_manage_unarchive':
                    Livewire.dispatch('svcr-manage-unarchive', { id: item.id });
                    break;
                case 'staff':
                    Livewire.dispatch('archiveStaff', { id: item.id });
                    break;
                case 'area':
                    Livewire.dispatch('archiveArea', { id: item.id });
                    break;
                case 'role':
                    Livewire.dispatch('archiveRole', { id: item.id });
                    break;
                case 'user':
                    Livewire.dispatch('archiveUser', { id: item.id });
                    break;
                case 'sr_role_assignment':
                    Livewire.dispatch('sr-archive-instructor', { id: item.id });
                    break;
                default:
                    console.log('Model not found');
            }
        }
    })
});

Livewire.on('batchDeleteServiceRoles', (data) => {
    data = data[0];
    if (confirm(data.message)) {
        Livewire.dispatch('deleteAllSelected');
    }
});
