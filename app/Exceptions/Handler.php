<?php

namespace App\Exceptions;

use App\Http\Controllers\Controller;
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
                $res = new Controller();
                if ($e instanceof AuthorizationException) {
                    return $res->error(trans('defaultMessages.auth.unauthorized'), 400);
                } elseif ($e instanceof UnauthorizedHttpException) {
                    return $res->error(trans('defaultMessages.auth.unauthorized'), 401);
                } elseif ($e instanceof NotFoundHttpException) {
                    return $res->error(trans('defaultMessages.auth.not_found_http_url'), 404);
                } elseif ($e instanceof ModelNotFoundException) {
                    return $res->error(trans('defaultMessages.auth.model_not_found'), 404);
                } elseif ($e instanceof AccessDeniedHttpException) {
                    return $res->error(trans('defaultMessages.auth.forbidden'), 403);
                } elseif ($e instanceof MethodNotAllowedHttpException) {
                    return $res->error(trans('defaultMessages.auth.method_not_allowed'), 405);
                }

            }
        });
    }
}
