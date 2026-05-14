<?php

namespace App\Services\Contracts;

interface SummaryServiceInterface
{
    public function getSummary(?string $fromDate = null, ?string $toDate = null): array;
}
