<?php

declare(strict_types=1);

namespace App\Modules\Shared\Strategies;

use Throwable;

final class Exception401 extends AbstractException
{
    protected function buildResponse(Throwable $e): array
    {
        return ['message' => 'Unauthorized'];
    }
}
