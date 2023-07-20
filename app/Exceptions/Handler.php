<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use App\Exceptions\CustomException;
use App\Exceptions\InvalidOrderException;
use Sentry\Laravel\Integration;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var string[]
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var string[]
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
      
       
    }
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof CustomException)  {
            return $exception->render($request);
        }
        return parent::render($request, $exception);
    }
   
}
