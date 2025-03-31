<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->api(prepend: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);

        $middleware->alias([
            'verified' => \App\Http\Middleware\EnsureEmailIsVerified::class,
            'token' => \App\Http\Middleware\CheckTokenExpiration::class,
        ]);

        //
    })
    ->withExceptions(function (Exceptions $exceptions) {

        // Custom 404 response
        $exceptions->render(function (NotFoundHttpException  $e) {
            $previous = $e->getPrevious();

            if ($previous instanceof ModelNotFoundException) {
                return response()->json([
                    'success' => false,
                    'message' => class_basename($previous->getModel()) . ' not found',
                ], 404);
            }

            return response()->json([
                'success' => false,
                'message' => 'Page not found',
            ], 404);

        });
    })->create();
