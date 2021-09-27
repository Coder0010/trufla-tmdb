<?php

namespace App\Exceptions;

use App;
use Exception;
use Throwable;
use InvalidArgumentException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Foundation\Http\Exceptions\MaintenanceModeException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
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
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $e
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $e)
    {
        if ($request->wantsJson()) {
            switch ($e) {
                case $e instanceof MethodNotAllowedHttpException:
                    return response()->json([
                        "payload" => [
                            "message" => "method not allowed",
                        ],
                    ], 405);
                    break;
                case $e instanceof NotFoundHttpException:
                    return response()->json([
                        "payload" => [
                            "message" => "route not found",
                        ],
                    ], 404);
                    break;
                case $e instanceof ValidationException:
                    return response()->json([
                        "payload" => [
                            "message" => "failed validation",
                            "errors"  => $e->errors()
                        ],
                    ], 422);
                    break;
                case $e instanceof ModelNotFoundException:
                    return response()->json([
                        "payload" => [
                            "message" => "record not found",
                        ],
                    ], 404);
                    break;
                case $e instanceof AuthenticationException:
                    return response()->json([
                        "payload" => [
                            "message" => "unauthenticated",
                        ],
                    ], 401);
                    break;
                case $e instanceof AuthorizationException:
                    return response()->json([
                        "payload" => [
                            "message" => "unauthorized",
                        ],
                    ], 403);
                    break;
                case $e instanceof MaintenanceModeException:
                    return response()->json([
                        "payload" => [
                            "message" => "maintenance mode",
                        ],
                    ], 200);
                    break;
                default:
                    return response()->json([
                        "payload" => [
                            "message" => $e->getMessage(),
                        ],
                    ], 500);
                    break;
            }
        }
        return parent::render($request, $e);
    }
}
