@foreach ($errors->all() as $error)
    <li class="font-normal text-base text-red-600 list-none">{{ $error }}</li>
@endforeach
