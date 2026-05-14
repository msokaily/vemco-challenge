<?php

namespace App\Services;

use App\Models\Visitor;
use App\Services\Contracts\VisitorServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class VisitorService implements VisitorServiceInterface
{
    public function getAll(?string $fromDate = null, ?string $toDate = null): Collection
    {
        $cacheKey = 'visitors:index:date:' . ($fromDate ?? 'all') . ':' . ($toDate ?? 'all');
        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($fromDate, $toDate) {
            return Visitor::query()
                ->with('location')
                ->when($fromDate, function ($query) use ($fromDate) {
                    $query->whereDate('date', '>=', $fromDate);
                })
                ->when($toDate, function ($query) use ($toDate) {
                    $query->whereDate('date', '<=', $toDate);
                })
                ->latest('date')
                ->get();
        });
    }

    public function create(array $data): Visitor
    {
        $visitor = Visitor::create($data);
        $visitor->load(['location', 'sensor']);
        Cache::flush();
        return $visitor;
    }
}
