<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class LivewireHelpers
{
    public static function componentExists($componentName)
    {
        //convert to component class name
        $componentName = Str::studly($componentName);
        // upper case the first letter of the component name
        $componentName = ucfirst($componentName);
        if (Str::contains($componentName, '.')) {
            $componentName = collect(explode('.', $componentName))->map(function ($part) {
                return ucfirst($part);
            })->implode('.');
        }
        // dd($componentName);
        $path = base_path('app/Livewire/' . str_replace('.', '/', $componentName) . '.php');
        // dd($path);
        return File::exists($path);
    }
}
