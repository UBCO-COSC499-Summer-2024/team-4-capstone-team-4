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

    public function setSettings(Request $request)
    {
        $settings = $request->all();
        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(
                ['user_id' => auth()->id(), 'key' => $key],
                ['value' => $value]
            );
        }
        return response()->json(['success' => true]);
    }
}
