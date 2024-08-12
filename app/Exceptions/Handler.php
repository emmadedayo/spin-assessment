<?php

namespace App\Exceptions;

use App\Helper\ApiResponse;
use Carbon\Exceptions\InvalidFormatException;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Throwable;

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
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Exception|Throwable $exception): Response|JsonResponse|\Symfony\Component\HttpFoundation\Response|\Illuminate\Http\RedirectResponse
    {
        $apiResponse = new ApiResponse();

        if ($exception instanceof ValidationException) {
            return $apiResponse->validation($exception->errors());
        } elseif ($exception instanceof ModelNotFoundException) {
            $fetchedModelName = Str::title(class_basename($exception->getModel()));
            $responseModelName = (count($exception->getIds()) > 1) ? Str::plural($fetchedModelName) : $fetchedModelName;
            $responseHelpingVerb = (count($exception->getIds()) > 1) ? 'are' : 'is';
            $lowercaseResponseModelName = Str::lower($responseModelName);

            return $apiResponse->failed(
                "{$responseModelName} {$responseHelpingVerb} not found.",
                Response::HTTP_NOT_FOUND
            );
        } elseif ($exception instanceof AuthorizationException) {
            return $apiResponse->failed($exception->getMessage(), Response::HTTP_FORBIDDEN);
        } elseif ($exception instanceof AuthenticationException) {
            return $apiResponse->failed($exception->getMessage(), Response::HTTP_UNAUTHORIZED);
        } elseif ($exception instanceof HttpResponseException) {
            return $apiResponse->failed($exception->getMessage());
        } elseif ($exception instanceof InvalidFormatException) {
            return $apiResponse->failed($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        } else {
            return $apiResponse->failed($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
