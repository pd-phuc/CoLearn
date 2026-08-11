<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;

class SettingService
{
    private const CACHE_PREFIX = 'settings:';

    private const CACHE_TTL = 3600; // 1 hour

    /**
     * Get a setting value by group and key.
     * Falls back to config/env if not found in database.
     */
    public function get(string $group, string $key, mixed $default = null): ?string
    {
        $cacheKey = self::CACHE_PREFIX.$group.'.'.$key;

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($group, $key, $default) {
            $setting = Setting::where('group', $group)->where('key', $key)->first();

            return $setting?->value ?? $default;
        });
    }

    /**
     * Set a setting value. Encrypts if flagged.
     */
    public function set(string $group, string $key, ?string $value, bool $encrypted = false): Setting
    {
        $storedValue = ($encrypted && $value) ? Crypt::encryptString($value) : $value;

        $setting = Setting::updateOrCreate(
            ['group' => $group, 'key' => $key],
            ['value' => $storedValue, 'is_encrypted' => $encrypted],
        );

        // Bust cache
        Cache::forget(self::CACHE_PREFIX.$group.'.'.$key);
        Cache::forget(self::CACHE_PREFIX.$group.':all');

        return $setting;
    }

    /**
     * Get all settings for a group as key-value array.
     */
    public function getGroup(string $group): array
    {
        $cacheKey = self::CACHE_PREFIX.$group.':all';

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($group) {
            return Setting::where('group', $group)
                ->get()
                ->pluck('value', 'key')
                ->toArray();
        });
    }

    /**
     * Bulk update settings for a group.
     */
    public function updateGroup(string $group, array $values, array $encryptedKeys = []): void
    {
        foreach ($values as $key => $value) {
            $this->set($group, $key, $value, in_array($key, $encryptedKeys));
        }
    }

    /**
     * Clear all cache for a group.
     */
    public function clearGroupCache(string $group): void
    {
        $settings = Setting::where('group', $group)->pluck('key');

        foreach ($settings as $key) {
            Cache::forget(self::CACHE_PREFIX.$group.'.'.$key);
        }

        Cache::forget(self::CACHE_PREFIX.$group.':all');
    }

    /**
     * Apply DB settings to Laravel config at runtime.
     * DB values override .env values.
     */
    public function applyToConfig(): void
    {
        // Mail
        $mail = $this->getGroup('mail');
        if (! empty($mail)) {
            if (! empty($mail['driver'])) {
                config(['mail.default' => $mail['driver']]);
            }
            if (! empty($mail['host'])) {
                config(['mail.mailers.smtp.host' => $mail['host']]);
            }
            if (! empty($mail['port'])) {
                config(['mail.mailers.smtp.port' => (int) $mail['port']]);
            }
            if (! empty($mail['username'])) {
                config(['mail.mailers.smtp.username' => $mail['username']]);
            }
            if (! empty($mail['password'])) {
                config(['mail.mailers.smtp.password' => $mail['password']]);
            }
            if (! empty($mail['encryption'])) {
                config(['mail.mailers.smtp.encryption' => $mail['encryption'] === 'null' ? null : $mail['encryption']]);
            }
            if (! empty($mail['from_address'])) {
                config(['mail.from.address' => $mail['from_address']]);
            }
            if (! empty($mail['from_name'])) {
                config(['mail.from.name' => $mail['from_name']]);
            }
        }

        // OAuth Google
        $google = $this->getGroup('oauth_google');
        if (! empty($google)) {
            if (! empty($google['client_id'])) {
                config(['services.google.client_id' => $google['client_id']]);
            }
            if (! empty($google['client_secret'])) {
                config(['services.google.client_secret' => $google['client_secret']]);
            }
            if (! empty($google['redirect_url'])) {
                config(['services.google.redirect' => $google['redirect_url']]);
            }
        }

        // OAuth Facebook
        $facebook = $this->getGroup('oauth_facebook');
        if (! empty($facebook)) {
            if (! empty($facebook['client_id'])) {
                config(['services.facebook.client_id' => $facebook['client_id']]);
            }
            if (! empty($facebook['client_secret'])) {
                config(['services.facebook.client_secret' => $facebook['client_secret']]);
            }
            if (! empty($facebook['redirect_url'])) {
                config(['services.facebook.redirect' => $facebook['redirect_url']]);
            }
        }

        // Storage (S3)
        $storage = $this->getGroup('storage');
        if (! empty($storage)) {
            if (! empty($storage['disk'])) {
                config(['filesystems.default' => $storage['disk']]);
            }
            if (! empty($storage['aws_key'])) {
                config(['filesystems.disks.s3.key' => $storage['aws_key']]);
            }
            if (! empty($storage['aws_secret'])) {
                config(['filesystems.disks.s3.secret' => $storage['aws_secret']]);
            }
            if (! empty($storage['aws_region'])) {
                config(['filesystems.disks.s3.region' => $storage['aws_region']]);
            }
            if (! empty($storage['aws_bucket'])) {
                config(['filesystems.disks.s3.bucket' => $storage['aws_bucket']]);
            }
        }
    }
}
