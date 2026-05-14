<?php

namespace App\Services;

use App\Models\Location;
use App\Services\Contracts\LocationServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class LocationService implements LocationServiceInterface
{
    private const CACHE_GROUP = 'locations';
    public function getAll(): Collection
    {
        $cacheKey = self::CACHE_GROUP . ':index';
        return Location::hydrate(Cache::tags([self::CACHE_GROUP])->remember($cacheKey, now()->addMinutes(10), function () {
            return Location::latest()->get()->toArray();
        }));
    }

    public function create(array $data): Location
    {
        $location = Location::create($data);
        Cache::tags([self::CACHE_GROUP, 'summary'])->flush();
        return $location;
    }
}
