<?php

namespace App\Services;

use App\Models\Country;
use Illuminate\Support\Facades\Cache;

class CacheService
{
    private const CACHE_TTL = 86400; // 24 hours

    public function getCountries()
    {
        return Cache::remember('countries', self::CACHE_TTL, function () {
            return Country::active()->ordered()->get();
        });
    }

    public function clearCountries(): void
    {
        Cache::forget('countries');
    }
}
