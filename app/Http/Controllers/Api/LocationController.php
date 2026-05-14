<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLocationRequest;
use App\Http\Resources\LocationResource;
use App\Services\Contracts\LocationServiceInterface;

class LocationController extends Controller
{
    public function __construct(
        private readonly LocationServiceInterface $locationService
    ) {}

    public function index()
    {
        $locations = $this->locationService->getAll();
        return $this->successResponse(LocationResource::collection($locations));
    }

    public function store(StoreLocationRequest $request)
    {
        $location = $this->locationService->create($request->validated());
        return $this->successResponse(new LocationResource($location), 'Location created successfully', 201);
    }
}
