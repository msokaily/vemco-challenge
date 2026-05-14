<?php

namespace App\Services;

use App\Models\Location;
use App\Services\Contracts\LocationServiceInterface;
use Illuminate\Database\Eloquent\Collection;

class LocationService implements LocationServiceInterface
{
    public function getAll(): Collection
    {
        return Location::latest()->get();
    }

    public function create(array $data): Location
    {
        return Location::create($data);
    }
}
