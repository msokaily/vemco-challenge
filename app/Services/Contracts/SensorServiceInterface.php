<?php

namespace App\Services\Contracts;

use App\Models\Sensor;

interface SensorServiceInterface
{
    public function getAll(?string $status = null): array;
    public function create(array $data): Sensor;
}
