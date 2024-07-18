<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $table = 'settings';

    protected $fillable = ['user_id', 'auth_method', 'theme', 'timezone', 'locale', 'language', 'key', 'value'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getSetting($key)
    {
        return $this->where('key', $key)->first();
    }

    public function findSetting($name) {
        // search key or predefined settings
        //, 'auth_method', 'theme', 'timezone', 'locale', 'language', 'key', 'value'
        if (in_array($name, ['auth_method', 'theme', 'timezone', 'locale', 'language'])) {
            return $this->where('key', $name)->first();
        } else {
            $setting = $this->where('key', $name)->first();
            if ($setting) {
                return $setting;
            } else {
                return $this->create([
                    'user_id' => auth()->id(),
                    'key' => $name,
                    'value' => null
                ]);
            }
        }
    }

    public function setSetting($key, $value)
    {
        $setting = $this->where('key', $key)->first();
        if ($setting) {
            $setting->value = $value;
            $setting->save();
        } else {
            $this->create([
                'user_id' => auth()->id(),
                'key' => $key,
                'value' => $value
            ]);
        }
    }

    public function getSettings()
    {
        return $this->where('user_id', auth()->id())->get();
    }

    public function setSettings($settings)
    {
        foreach ($settings as $key => $value) {
            $this->setSetting($key, $value);
        }
    }

    public function getTheme()
    {
        return $this->getSetting('theme')->value;
    }

    public function setTheme($theme)
    {
        $this->setSetting('theme', $theme);
    }

    public function getTimezone()
    {
        return $this->getSetting('timezone')->value;
    }

    public function setTimezone($timezone)
    {
        $this->setSetting('timezone', $timezone);
    }

    public function getLocale()
    {
        return $this->getSetting('locale')->value;
    }

    public function setLocale($locale)
    {
        $this->setSetting('locale', $locale);
    }

    public function getLanguage()
    {
        return $this->getSetting('language')->value;
    }

    public function setLanguage($language)
    {
        $this->setSetting('language', $language);
    }

    public function getAuthMethod()
    {
        return $this->getSetting('auth_method')->value;
    }

    public function setAuthMethod($auth_method)
    {
        $this->setSetting('auth_method', $auth_method);
    }
}
