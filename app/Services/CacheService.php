<?php

namespace App\Services;

use App\Models\Country;
use App\Models\Specialty;
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

    public function getSpecialties()
    {
        return Cache::remember('specialties', self::CACHE_TTL, function () {
            return Specialty::active()->ordered()->get();
        });
    }

    public function clearSpecialties(): void
    {
        Cache::forget('specialties');
    }
}
