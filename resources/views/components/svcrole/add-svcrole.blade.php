<div class="content">
    <h1 class="nos flex">
        Add Service Roles
        {{-- <section class="mini-toolbar">
            <div class="toolbar-section-item">
            </div>
        </section> --}}
        <button class="right">
            <span class="button-title">Create New</span>
            <span class="material-symbols-outlined">add</span>
        </button>
        <button>
            <span class="button-title">See All</span>
            <span class="material-symbols-outlined">visibility</span>
        </button>
    </h1>
    <section id="import-data" class="active glass">
        <form id="account-form" class="form">
            <div class="horizontal grouped">
                <div class="form-group">
                    <div class="grouped">
                        <div class="form-item">
                            <label class="form-label">Role:</label>
                            <input class="form-input" type="text" name="role">
                        </div>
                        <div class="form-item">
                            <label class="form-label">Area:
                            </label>
                            {{-- use the dropdown --}}
                            <x-dropdown-element 
                                id="areaDropdown" 
                                class="toolbar-dropdown" 
                                title="Area"
                                :values="['Area 1' => 'Area 1', 'Area 2' => 'Area 2', 'Area 3' => 'Area 3']"
                                preIcon="category"
                                searchable="true">
                            </x-dropdown-element>
                        </div>
                        <div class="form-item">
                            <label class="input-label">Description:</label>
                            <textarea class="form-input form-textarea" id="description" name="description" rows="4" cols="30"></textarea>
                        </div>
                        <div class="form-item">
                            <label class="input-label">Add Extra Hours:</label>
                            <button class="button">
                                <span class="button-title">Add</span>
                                <span class="material-symbols-outlined">add</span>
                            </button>
                        </div>
                        <div class="form-item bottom">
                            <input class="form-input" type="submit" name="submit" value="Cancel" />
                            <input class="form-input" type="submit" name="submit" value="Add" />
                        </div>
                    </div>
                </div>
                <x-calendar style="display: none" />
            </div>
        </form>
    </section>
    <section class="link-bar">
        <x-link href="{{ route('svcroles') }}" title="{{ __('Dashboard') }}" :active="request()->is('svcroles')" />
        <x-link href="{{ route('svcroles.add') }}" title="{{ __('Add Service Role') }}" :active="request()->is('svcroles/add')" />
        <x-link href="{{ route('svcroles.manage') }}" title="{{ __('Manage Service Roles') }}" :active="request()->is('svcroles/manage')" />
        <x-link href="{{ route('svcroles.requests') }}" title="{{ __('Requests') }}" :active="request()->is('svcroles/requests')" />
        <x-link href="{{ route('svcroles.logs') }}" title="{{ __('Audit Logs') }}" :active="request()->is('svcroles/audit-logs')" />
    </section>
</div>

{{-- add extra hours button toggles the calendar --}}
<script>
    document.querySelector('.form-item button').addEventListener('click', function(e) {
        e.preventDefault();
        const ehCal = document.querySelector('div.calendar');
        if (ehCal.style.display === 'none') {
            ehCal.style.display = 'block';
        } else {
            ehCal.style.display = 'none';
        }
    });
</script>