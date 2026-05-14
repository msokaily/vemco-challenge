<?php

namespace App\Services\Contracts;

use App\Models\Visitor;
use Illuminate\Database\Eloquent\Collection;

interface VisitorServiceInterface
{
    public function getAll(?string $date = null): Collection;
    public function create(array $data): Visitor;
}
