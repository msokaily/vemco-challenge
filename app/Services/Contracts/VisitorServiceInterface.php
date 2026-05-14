<?php

namespace App\Services\Contracts;

use App\Models\Visitor;
use Illuminate\Database\Eloquent\Collection;

interface VisitorServiceInterface
{
    public function getAll(?string $fromDate = null, ?string $toDate = null): Collection;
    public function create(array $data): Visitor;
}
