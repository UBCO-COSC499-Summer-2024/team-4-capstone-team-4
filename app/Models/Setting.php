<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $table = 'settings';

    protected $fillable = ['user_id', 'auth_method', 'theme', 'timezone', 'locale', 'language', 'custom'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getSetting($key)
    {
        if ($this->findSetting($key)) {
            return $this->findSetting($key)->value;
        } else {
            return null;
        }
    }

    public function findSetting($name) {
        // search key or predefined settings
        //, 'auth_method', 'theme', 'timezone', 'locale', 'language', 'key', 'value'
        if (in_array($name, ['auth_method', 'theme', 'timezone', 'locale', 'language'])) {
            return $this->where('key', $name)->first();
        } else {
            // search custom which is a jsonb

        }
    }

    public function setSetting($key, $value)
    {
        $setting = $this->findSetting($key);
        if ($setting) {
            if(in_array($key, ['auth_method', 'theme', 'timezone', 'locale', 'language'])) {
                $setting[$key] = $value;
            } else {
                // update custom jsonb
                $setting->custom[$key] = $value;
            }
            $setting->save();
        }
    }

    public function showSettings() {
        $settingsArray = [];
        $settingsArray['auth_method'] = $this->getAuthMethod();
        $settingsArray['theme'] = $this->getTheme();
        $settingsArray['timezone'] = $this->getTimezone();
        $settingsArray['locale'] = $this->getLocale();
        $settingsArray['language'] = $this->getLanguage();
        $custom = $this->custom;
        foreach ($custom as $key => $value) {
            $settingsArray[$key] = $value;
        }
        return $settingsArray;
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

    public function getCustomSetting($key)
    {
        return $this->custom[$key];
    }

    public function setCustomSetting($key, $value)
    {
        $this->custom[$key] = $value;
    }
}
