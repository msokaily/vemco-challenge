<?php

namespace App\Services;

use App\Services\Contracts\SummaryServiceInterface;
use Illuminate\Support\Facades\Cache;

class SummaryService implements SummaryServiceInterface
{
    public function getSummary(?string $fromDate = null, ?string $toDate = null): array
    {
        $cacheKey = 'summary:index:date:' . ($fromDate ?? 'all') . ':' . ($toDate ?? 'all');
        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($fromDate, $toDate) {
            $visitorCount = app(VisitorService::class)->getAll($fromDate, $toDate)->count();
            $sensorCount = app(SensorService::class)->getAll()->total();
            $locationCount = app(LocationService::class)->getAll()->count();

            return [
                'total_visitors' => $visitorCount,
                'total_sensors' => $sensorCount,
                'total_locations' => $locationCount,
            ];
        });
    }
}
