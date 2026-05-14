<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FilterSensorRequest;
use App\Http\Requests\StoreSensorRequest;
use App\Http\Resources\SensorResource;
use App\Services\Contracts\SensorServiceInterface;

class SensorController extends Controller
{
    public function __construct(
        private readonly SensorServiceInterface $sensorService
    ) {}

    public function index(FilterSensorRequest $request)
    {
        $sensors = $this->sensorService->getAll($request->validated()?->status ?? null);
        return $this->successResponse($sensors);
    }

    public function store(StoreSensorRequest $request)
    {
        $sensor = $this->sensorService->create($request->validated());
        return $this->successResponse(new SensorResource($sensor), 'Sensor created successfully', 201);
    }
}
