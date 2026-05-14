<?php

namespace App\Services\Contracts;

use App\Models\Location;
use Illuminate\Database\Eloquent\Collection;

interface LocationServiceInterface
{
    public function getAll(): Collection;
    public function create(array $data): Location;
}
