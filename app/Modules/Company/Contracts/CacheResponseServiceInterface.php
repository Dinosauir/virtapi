<?php

declare(strict_types=1);


namespace App\Modules\Company\Contracts;

use Illuminate\Http\JsonResponse;

interface CacheResponseServiceInterface
{
    public function getCompanies(string $page = "1"): JsonResponse;
}