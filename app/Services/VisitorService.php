<?php

namespace App\Services;

use App\Http\Resources\VisitorResource;
use App\Models\Visitor;
use App\Services\Contracts\VisitorServiceInterface;
use Illuminate\Support\Facades\Cache;

class VisitorService implements VisitorServiceInterface
{
    private const CACHE_GROUP = 'visitors';
    public function getAll(?string $fromDate = null, ?string $toDate = null): array
    {
        $cacheKey = self::CACHE_GROUP . ':index:date:' . ($fromDate ?? 'all') . ':' . ($toDate ?? 'all');
        return Cache::tags([self::CACHE_GROUP])->remember($cacheKey, now()->addMinutes(10), function () use ($fromDate, $toDate) {
            return VisitorResource::collection(Visitor::query()
                ->with(['location', 'sensor'])
                ->when($fromDate, function ($query) use ($fromDate) {
                    $query->whereDate('date', '>=', $fromDate);
                })
                ->when($toDate, function ($query) use ($toDate) {
                    $query->whereDate('date', '<=', $toDate);
                })
                ->latest('date')
                ->get())->response()
                ->getData(true)['data'];
        });
    }

    public function create(array $data): Visitor
    {
        $visitor = Visitor::create($data);
        $visitor->load(['location', 'sensor']);
        Cache::tags([self::CACHE_GROUP, 'summary'])->flush();
        return $visitor;
    }
}
