<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function getSettings()
    {
        $settings = Setting::where('user_id', auth()->id())->get();
        return response()->json($settings);
    }
}
