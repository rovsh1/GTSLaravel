<?php

namespace App\Admin\Exceptions;

use App\Admin\Support\Facades\Layout;
use App\Core\Support\Facades\AppContext;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function render($request, Throwable $e)
    {
        if ($request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return $this->_renderJsonException($e);
        }

        if ($this->isNotFoundException($e)) {
            return $this->_renderNotFound($e);
        } else {
            if (app()->has('layout')) {
                //TODO edit it
                Layout::configure();
            }

            return parent::render($request, $e);
        }
    }

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
        });
    }

    private function _renderNotFound($e): Response
    {
        Layout::configure();

        return response()->view('errors.404', [
            'exception' => $e,
            'title' => 'Not Found',
            'message' => $e->getMessage()
        ], 404, []);
    }

    private function _renderJsonException(\Throwable $e): JsonResponse
    {
        $httpCode = match (true) {
            $e instanceof NotFoundHttpException => 404,
            $e instanceof HttpException => $e->getStatusCode(),
            default => 500
        };

        return new JsonResponse([
            'error' => $e->getCode(),
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], $httpCode);
    }

    protected function isNotFoundException(Throwable $e)
    {
        return $e instanceof NotFoundHttpException
            || $e instanceof ModelNotFoundException
            || $e instanceof MethodNotAllowedHttpException;
    }

    protected function context(): array
    {
        if (app()->has('app-context')) {
            return AppContext::get();
        } else {
            return [];
        }
    }
}
