<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Contracts\SummaryServiceInterface;

class SummaryController extends Controller
{
    public function __construct(
        private readonly SummaryServiceInterface $summaryService
    ) {}

    public function index()
    {
        return $this->successResponse($this->summaryService->getSummary());
    }
}
