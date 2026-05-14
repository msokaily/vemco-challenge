<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FilterSummaryRequest;
use App\Services\Contracts\SummaryServiceInterface;

class SummaryController extends Controller
{
    public function __construct(
        private readonly SummaryServiceInterface $summaryService
    ) {}

    public function index(FilterSummaryRequest $request)
    {
        $params = $request->validated();
        $summary = $this->summaryService->getSummary($params['from_date'] ?? null, $params['to_date'] ?? null, $params['sensor_status'] ?? null);
        return $this->successResponse($summary);
    }
}
