<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;

class LivewireHelpers
{
    public static function componentExists($componentName)
    {
        $path = base_path('app/Http/Livewire/' . str_replace('.', '/', $componentName) . '.php');
        return File::exists($path);
    }
}
