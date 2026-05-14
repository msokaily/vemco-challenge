<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FilterVisitorRequest;
use App\Http\Requests\StoreVisitorRequest;
use App\Http\Resources\VisitorResource;
use App\Services\Contracts\VisitorServiceInterface;

class VisitorController extends Controller
{
    public function __construct(
        private readonly VisitorServiceInterface $visitorService
    ) {}

    public function index(FilterVisitorRequest $request)
    {
        $params = $request->validated();
        $visitors = $this->visitorService->getAll($params?->from_date ?? null, $params?->to_date ?? null);
        return $this->successResponse(VisitorResource::collection($visitors));
    }
    public function store(StoreVisitorRequest $request)
    {
        $visitor = $this->visitorService->create($request->validated());
        return $this->successResponse(new VisitorResource($visitor), 'Visitor created successfully', 201);
    }
}
