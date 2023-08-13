<?php

declare(strict_types=1);


namespace App\Modules\Shared\Strategies;


use Throwable;

abstract class AbstractException
{
    final public function handle(Throwable $e): array
    {
        $response = $this->buildResponse($e);

        if (config('app.debug')) {
            $response['code'] = $e->getCode();
            $response['error_message'] = $e->getMessage();
        }

        return $response;
    }

    abstract protected function buildResponse(Throwable $e): array;
}