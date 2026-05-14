<?php

namespace App\Services;

use App\Models\Sensor;
use App\Services\Contracts\SensorServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class SensorService implements SensorServiceInterface
{
    public function getAll(?string $status = null): LengthAwarePaginator
    {
        $page = request()->query('page', 1);
        $perPage = request()->query('per_page', 15);
        $cacheKey = "sensors:index:status:" . ($status ?? '') . ":page:{$page}:per_page:{$perPage}";
        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($status, $page, $perPage) {
            return Sensor::query()
                ->with('location')
                ->when($status, function ($query) use ($status) {
                    $query->where('status', $status);
                })
                ->latest()
                ->paginate($perPage, ['*'], 'page', $page);
        });
    }

    public function create(array $data): Sensor
    {
        $sensor = Sensor::create($data);
        $sensor->load('location');
        Cache::flush();
        return $sensor;
    }
}
