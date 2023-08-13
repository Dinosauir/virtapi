<?php

namespace App\Modules\Shared\Exceptions;

use App\Modules\Shared\Services\ExceptionHandlerService;
use Illuminate\Contracts\Container\Container;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as ResponseHttp;
use Throwable;

class Handler extends ExceptionHandler
{
    public function __construct(Container $container, private readonly ExceptionHandlerService $handlerService)
    {
        parent::__construct($container);
    }

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    final public function register(): void
    {
        $this->reportable(function (Throwable $e) {
        });
    }

    final public function render($request, Throwable $e): Response|JsonResponse|RedirectResponse|ResponseHttp
    {
        if ($this->handlerService->validateRender($e, $request->wantsJson())) {
            return $this->handlerService->buildExceptionResponse($this->prepareException($e));
        }

        return parent::render($request, $e);
    }
}
