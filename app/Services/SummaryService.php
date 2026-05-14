<?php

namespace App\Services;

use App\Enums\SensorStatus;
use App\Models\Sensor;
use App\Models\Visitor;
use App\Services\Contracts\SummaryServiceInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class SummaryService implements SummaryServiceInterface
{
    private const CACHE_GROUP = 'summary';

    public function getSummary(): array
    {
        $fromDate = Carbon::today()->subDays(6)->toDateString();
        $toDate = Carbon::today()->toDateString();

        $cacheKey = self::CACHE_GROUP . ':index:from:' . $fromDate . ':to:' . $toDate;

        return Cache::tags([self::CACHE_GROUP])->remember(
            $cacheKey,
            now()->addMinutes(10),
            function () use ($fromDate, $toDate) {
                $totalVisitorsPast7Days = Visitor::query()
                    ->whereBetween('date', [$fromDate, $toDate])
                    ->sum('count');

                $activeSensorsCount = Sensor::query()
                    ->where('status', SensorStatus::ACTIVE->value)
                    ->count();

                $inactiveSensorsCount = Sensor::query()
                    ->where('status', SensorStatus::INACTIVE->value)
                    ->count();

                return [
                    'period' => [
                        'from' => $fromDate,
                        'to' => $toDate,
                    ],
                    'total_visitors_past_7_days' => (int) $totalVisitorsPast7Days,
                    'sensors' => [
                        'active' => $activeSensorsCount,
                        'inactive' => $inactiveSensorsCount,
                    ],
                ];
            }
        );
    }
}
