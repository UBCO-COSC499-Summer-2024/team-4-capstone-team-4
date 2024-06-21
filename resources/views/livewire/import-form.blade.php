<div>
    @if(session('success'))
        {{ session('success') }}
    @endif

    <form wire:submit="handleClick" action="">
        <input wire:model="id" type="number" placeholder="ID">
        
        @error('id')
            <span>{{ $message }}</span>
        @enderror

        <input wire:model="firstname" type="text" placeholder="First Name">

        @error('firstname')
        <span>{{ $message }}</span>
        @enderror

        <input wire:model="lastname" type="text" placeholder="Last Name">

        @error('lastname')
        <span>{{ $message }}</span>
        @enderror

        <label for="file">CSV File</label>
        <input wire:model='file' type="file" id="file">

        @error('file')
        <span>{{ $message }}</span>
        @enderror

        <button>Create</button>

    </form>

    <div>
        @foreach($users as $user) 
            <div>{{ $user->firstname }}</div>
        @endforeach
    </div>

    {{ $users->links() }}
</div>
