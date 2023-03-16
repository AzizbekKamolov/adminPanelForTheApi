<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
//        $this->reportable(function (Throwable $e) {
//            //
//        });
        $this->renderable(function (\Exception $e, $request) {
            if ($request->is('api/*')) {
                if ($e instanceof AuthorizationException) {
                    return errorResponse(trans('defaultMessages.auth.unauthorized'), 'error', 400);
                } elseif ($e instanceof UnauthorizedHttpException) {
                    return errorResponse(trans('defaultMessages.auth.unauthorized'), 'error', 401);
                } elseif ($e instanceof NotFoundHttpException) {
                    return errorResponse(trans('defaultMessages.auth.not_found_http_url'), 'error', 404);
                } elseif ($e instanceof ModelNotFoundException) {
                    return errorResponse(trans('defaultMessages.auth.model_not_found'), 'error', 404);
                } elseif ($e instanceof AccessDeniedHttpException) {
                    return errorResponse(trans('defaultMessages.auth.forbidden'), 'error', 403);
                } elseif ($e instanceof MethodNotAllowedHttpException) {
                    return errorResponse(trans('defaultMessages.auth.method_not_allowed'), 'error', 405);
                }

            }
        });
    }
}
