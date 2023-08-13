<?php

declare(strict_types=1);


namespace App\Modules\Shared\Services;

use App\Modules\Shared\Strategies\AbstractException;
use App\Modules\Shared\Strategies\ExceptionDefault;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class ExceptionHandlerService
{
    final public function validateRender(\Throwable $e, bool $wantsJson): bool
    {
        return (!$e instanceof HttpResponseException) && (!$e instanceof ValidationException) && $wantsJson;
    }

    final public function buildExceptionResponse(\Throwable $e): JsonResponse
    {
        $statusCode = $this->resolveStatusCode($e);
        $strategy = $this->resolveStrategy($statusCode);

        $response = $strategy->handle($e);

        return response()->json($response, $statusCode);
    }

    private function resolveStatusCode(\Throwable $e): int
    {
        return method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;
    }

    private function resolveStrategy(int $statusCode): AbstractException
    {
        $class = $this->buildNamespace().'\\'.$this->buildName($statusCode);

        return class_exists($class) ? new $class : new ExceptionDefault();
    }

    private function buildNamespace(): string
    {
        return str_replace('Services', 'Strategies', __NAMESPACE__);
    }

    private function buildName(int $statusCode): string
    {
        return 'Exception'.$statusCode;
    }
}