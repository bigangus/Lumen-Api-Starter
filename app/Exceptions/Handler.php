<?php

namespace App\Exceptions;

use App\Http\Responses\Facade\HttpResponse;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param Throwable $e
     * @return void
     *
     * @throws Exception
     */
    public function report(Throwable $e): void
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request $request
     * @param Throwable $e
     *
     * @return Response
     */
    public function render($request, Throwable $e): Response
    {
        $code = 500;

        $data = App::isLocal() ? $this->convertExceptionToArray($e) : [];

        if ($e instanceof ModelNotFoundException) {
            $e = new NotFoundHttpException($e->getMessage(), $e);
        } elseif ($e instanceof AuthorizationException) {
            $e = new HttpException($e->status() ?? 403, $e->getMessage());
        } elseif ($e instanceof ValidationException && $e->getResponse()) {
            $code = $e->getResponse()->getStatusCode();
            $data = json_decode($e->getResponse()->getContent(), true);
        }

        if (method_exists($e, 'getStatusCode')) {
            $code = $e->getStatusCode();
        }

        return HttpResponse::error($e->getMessage(), $data, $code);
    }
}
