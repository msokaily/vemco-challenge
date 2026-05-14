<?php

namespace App\Services\Contracts;

use App\Models\Sensor;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface SensorServiceInterface
{
    public function getAll(?string $status = null): LengthAwarePaginator;
    public function create(array $data): Sensor;
}
