<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Http\JsonResponse;
use Illuminate\Auth\AuthenticationException;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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

        $this->renderable(function (AuthenticationException $e, $request) {
            return response()->json(
                    [
                        "status" => 401,
                        'success' => false,
                        'message' => __('unauthenticated')
                    ],
                401);
            });

            $this->reportable(function (Throwable $e) {

        });
    }

    public function render($request, Throwable $e)
    {
        if ($e instanceof UnauthorizedException) {
            $responseData = [
                "status" => 401,
                'success' => false,
                "message" => 'Authorization failed'
            ];

            // Prepare and return a JSON response
            return new JsonResponse($responseData, $responseData["status"]);
        }

        // Default exception handling for other types of exceptions
        return parent::render($request, $e);
    }
}
