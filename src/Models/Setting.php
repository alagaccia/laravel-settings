<?php

namespace Alagaccia\LaravelSettings\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Setting extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable()
    {
        return config('laravel-settings.table_name', 'settings');
    }

    /**
     * Get a setting value by code.
     *
     * @param string $code
     * @param mixed $default
     * @return mixed
     */
    public static function getByCode(string $code, $default = null)
    {
        $setting = static::where('code', $code)->first();

        return $setting ? $setting->value : $default;
    }

    /**
     * Set a setting value by code.
     *
     * @param string $code
     * @param string $value
     * @param string|null $category
     * @param string|null $label
     * @return static
     */
    public static function setByCode(string $code, string $value, ?string $category = null, ?string $label = null)
    {
        return static::updateOrCreate(
            ['code' => $code],
            [
                'value' => $value,
                'category' => $category ?? 'general',
                'label' => $label ?? $code,
            ]
        );
    }

    /**
     * Get all settings for a specific category.
     *
     * @param string $category
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getByCategory(string $category)
    {
        return static::where('category', $category)->get();
    }
}
