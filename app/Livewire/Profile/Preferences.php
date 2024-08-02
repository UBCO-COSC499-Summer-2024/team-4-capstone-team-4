<?php

namespace App\Livewire\Profile;

use App\Models\AuditLog;
use App\Models\AuthMethod;
use App\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Livewire\Component;

class Preferences extends Component
{

    public $settings;
    public $timezone;
    public $locale;
    public $language;
    public $theme;
    public $auth_method;
    public $auth_method_id;
    public $custom;

    protected $rules = [
        'settings.theme' => 'required',
        'settings.timezone' => 'required',
        'settings.locale' => 'required',
        'settings.language' => 'required',
        'settings.auth_method_id' => 'required|exists:auth_methods,id',
        // 'settings.custom' => 'json',
    ];

    public function mount()
    {
        $this->settings = auth()->user()->settings;
        $this->timezone = $this->settings['timezone'];
        $this->locale = $this->settings['locale'];
        $this->theme = $this->settings['theme'];
        $this->language = $this->settings['language'];
        $this->auth_method_id = $this->settings['auth_method_id'];
        $this->auth_method = AuthMethod::find($this->auth_method_id);
        if ($this->settings['custom'] === null) {
            $this->custom = [];
        } else {
            $this->custom = is_string($this->settings['custom']) ? json_decode($this->settings['custom'], true) : $this->settings['custom'];
        }
    }

    public function render()
    {
        return view('livewire.profile.preferences');
    }

    public function savePreferences()
    {
        $audit_user = User::find((int) auth()->user()->id)->getName();
        try {
            $this->validate();
            $oldValue = $this->settings->getOriginal();
            $this->settings['timezone'] = $this->timezone;
            $this->settings['theme'] = $this->theme;
            $this->settings['locale'] = $this->locale;
            $this->settings['language'] = $this->language;
            $this->settings['auth_method_id'] = $this->auth_method->id;
            $this->settings['custom'] = json_encode($this->custom);
            $this->settings->save();
            $this->dispatch('show-toast', [
                'message' => 'Preferences saved successfully!',
                'type' => 'success'
            ]);
            session([
                'timezone' => $this->timezone,
                'locale' => $this->locale,
                'language' => $this->language,
                'theme' => $this->theme,
            ]);
            App::setLocale($this->locale);
            Config::set('app.theme', $this->theme);
            AuditLog::create([
                'user_id' => (int) auth()->user()->id,
                'user_alt' => $audit_user,
                'action' => 'update',
                'table_name' => 'settings',
                'operation_type' => 'UPDATE',
                'old_value' => json_encode($oldValue),
                'new_value' => json_encode($this->settings->getAttributes()),
                'description' => $audit_user . ' updated their preferences',
            ]);
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'message' => 'Error saving preferences: ' . $e->getMessage(),
                'type' => 'error'
            ]);
            AuditLog::create([
                'user_id' => (int) auth()->user()->id,
                'user_alt' => $audit_user,
                'action' => 'error',
                'table_name' => 'settings',
                'operation_type' => 'UPDATE',
                'description' => 'Error updating preferences for ' . $audit_user . '\n'. $e->getMessage(),
            ]);
        } finally {
            $url = route('profile.show');
            header("Location: $url");
            exit();
        }
    }

    public function setTimezone($timezone)
    {
        $this->timezone = $timezone;
    }

    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    public function setLanguage($language)
    {
        $this->language = $language;
    }

    public function setTheme($theme)
    {
        $this->theme = $theme;
    }

    // public function setAuthMethod($auth_method)
    // {
    //     $this->settings['auth_method'] = $auth_method;
    // }

    public function updated($field)
    {
        $this->validateOnly($field);
    }
}
