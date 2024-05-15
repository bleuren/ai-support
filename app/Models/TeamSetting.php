<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class TeamSetting extends Model
{
    protected $fillable = [
        'team_id',
        'key',
        'description',
        'value',
    ];

    /**
     * Generates the full cache key for a given team setting key.
     *
     * @param  int  $teamId  The team ID.
     * @param  string  $key  The key of the setting.
     * @return string The full cache key.
     */
    protected static function cacheKey(int $teamId, string $key): string
    {
        $prefix = config('settings.cache_prefix', 'settings.');

        return "{$prefix}team_{$teamId}_{$key}";
    }

    protected static function booted(): void
    {
        static::saved(function (TeamSetting $setting) {
            $cacheKey = self::cacheKey($setting->team_id, $setting->key);
            Cache::forget($cacheKey);
        });

        static::deleted(function (TeamSetting $setting) {
            $cacheKey = self::cacheKey($setting->team_id, $setting->key);
            Cache::forget($cacheKey);
        });
    }

    public static function get(int $teamId, string $key, mixed $default = null): mixed
    {
        $cacheKey = self::cacheKey($teamId, $key);

        return Cache::rememberForever($cacheKey, function () use ($teamId, $key) {
            return self::where('team_id', $teamId)->where('key', $key)->first()->value ?? null;
        }) ?? $default;
    }

    public static function set(int $teamId, string $key, mixed $value, ?string $description = null): self
    {
        $setting = self::updateOrCreate(
            ['team_id' => $teamId, 'key' => $key],
            ['value' => $value, 'description' => $description]
        );

        $cacheKey = self::cacheKey($teamId, $key);
        Cache::forever($cacheKey, $value);

        return $setting;
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
