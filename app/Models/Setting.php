<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Setting extends Model
{
    use HasFactory;

    protected $table = 'settings';

    protected $fillable = ['user_id', 'auth_method_id', 'theme', 'timezone', 'locale', 'language', 'custom'];

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
        $predefined = ['auth_method_id', 'theme', 'timezone', 'locale', 'language'];
        if (in_array($name, $predefined)) {
            return $this->where('user_id', auth()->id())->first()[$name];
        } else {
            // search custom which is a jsonb
            return $this->where('user_id', auth()->id())->where('custom->' . $name, '!=', null)->first();
        }
    }

    public function setSetting($key, $value) {
        $setting = $this->findSetting($key);
        $audit_user = User::find((int) auth()->id())->getName();
        if ($setting) {
            if(in_array($key, ['auth_method_id', 'theme', 'timezone', 'locale', 'language'])) {
                $setting[$key] = $value;
            } else {
                // update custom jsonb
                $setting->custom[$key] = $value;
            }
            $oldValue = $this->getOriginal();
            $setting->save();

            AuditLog::create([
                'user_id' => auth()->id(),
                'user_alt' => $audit_user,
                'action' => 'update',
                'table_name' => 'settings',
                'old_value' => json_encode($oldValue),
                'new_value' => json_encode($this->getAttributes()),
                'operation_type' => 'update',
                'description' => 'Setting ' . $key . ' updated to ' . $value . ' for ' . $audit_user,
            ]);
        }
    }

    public function showSettings() {
        $settingsArray = [];
        $settingsArray['auth_method_id'] = $this->authMethod()->first()->id();
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

    public function getTheme() {
        return $this->getSetting('theme')->value;
    }

    public function setTheme($theme) {
        $this->setSetting('theme', $theme);
    }

    public function getTimezone() {
        return $this->getSetting('timezone')->value;
    }

    public function setTimezone($timezone) {
        $this->setSetting('timezone', $timezone);
    }

    public function getLocale() {
        return $this->getSetting('locale')->value;
    }

    public function setLocale($locale) {
        $this->setSetting('locale', $locale);
    }

    public function getLanguage() {
        return $this->getSetting('language')->value;
    }

    public function setLanguage($language) {
        $this->setSetting('language', $language);
    }

    public function authMethod() {
        return $this->belongsTo(AuthMethod::class);
    }

    public function setAuthMethod($auth_method_id)
    {
        $this->setSetting('auth_method_id', $auth_method_id);
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
