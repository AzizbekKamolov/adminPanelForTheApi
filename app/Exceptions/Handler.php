<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
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
        $this->renderable(function (\Exception $e, $request){
            if ($request->is('api/*')){
//                if ($e instanceof NotFoundHttpException) {
//                    return errorResponse("Bunday url maydoni mavjud emas", 'error', $request->id);
//                }elseif ($e instanceof AuthorizationException) {
//                    return errorResponse("Bunday url maydoni mavjud emas", 'error', $request->id);
//                }elseif ($e instanceof ModelNotFoundException) {
//                    return response()->json([
//                        'error' => 'Entry for '.str_replace('App', '', $e->getModel()).' not found'], 404);
//                }
                if (auth()->check() && $this->isHttpException($e)){
                    $langs = ['uz', 'ru', 'en'];
                    if (in_array(app()->getLocale(), $langs)){
                        $lang = app()->getLocale();
                    }else{
                        $lang = 'uz';
                    }
                    $arr = [400, 401, 403, 404, 405, 500];
                        $code = $e->getStatusCode();
                        dd($request->id);
                        if (in_array($code, $arr)) {
                            return response()->json([
                                'message' => config('myVariables.messages')[$lang][$code],
                                'code' => $code
                            ]);
                        }
                }
            }
        });
    }
}
