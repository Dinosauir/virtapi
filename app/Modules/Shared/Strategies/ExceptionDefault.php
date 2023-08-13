<?php

declare(strict_types=1);

namespace App\Modules\Shared\Strategies;

use Throwable;

final class ExceptionDefault extends AbstractException
{
    protected function buildResponse(Throwable $e): array
    {
        return ['message' => 'Server error'];
    }
}
