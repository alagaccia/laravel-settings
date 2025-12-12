<?php

use Alagaccia\LaravelSettings\Models\Setting;

if (! function_exists('getSetting')) {
    /**
     * Get a setting value by code.
     *
     * @param string $code
     * @param mixed $default
     * @return mixed
     */
    function getSetting(string $code, $default = null)
    {
        $setting = Setting::getByCode($code);
        
        if (!$setting) {
            return $default;
        }

        // Cast value based on type
        return match($setting->type) {
            'csv' => !empty($setting->value) ? explode(',', $setting->value) : [],
            'boolean' => filter_var($setting->value, FILTER_VALIDATE_BOOLEAN),
            'integer' => (int) $setting->value,
            'float' => (float) $setting->value,
            'json' => json_decode($setting->value, true),
            default => $setting->value,
        };
    }
}

if (! function_exists('setSetting')) {
    /**
     * Set a setting value by code.
     *
     * @param string $code
     * @param string $value
     * @param string|null $category
     * @param string|null $label
     * @return \Alagaccia\LaravelSettings\Models\Setting
     */
    function setSetting(string $code, string $value, ?string $category = null, ?string $label = null)
    {
        return Setting::setByCode($code, $value, $category, $label);
    }
}
