<?php

namespace App\Services\Contracts;

use App\Models\Visitor;

interface VisitorServiceInterface
{
    public function getAll(?string $fromDate = null, ?string $toDate = null): array;
    public function create(array $data): Visitor;
}
