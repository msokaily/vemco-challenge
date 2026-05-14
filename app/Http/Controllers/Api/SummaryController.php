<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FilterSummaryRequest;
use App\Http\Resources\SummaryResource;
use App\Services\Contracts\SummaryServiceInterface;

class SummaryController extends Controller
{
    public function __construct(
        private readonly SummaryServiceInterface $summaryService
    ) {}

    public function index(FilterSummaryRequest $request)
    {
        $summary = $this->summaryService->getSummary($request->validated());
        return $this->successResponse(SummaryResource::collection($summary));
    }
}
