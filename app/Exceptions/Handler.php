<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Auth; 
use Throwable;

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

    protected function unauthenticated($request, 
        AuthenticationException $exception)
    {
        $url = route('home');

        if ($request->is('admin') || $request->is('admin/*')) {
            $url = route('login.admin');
        }

        if ($request->is('teacher') || $request->is('teacher/*')) {
            $url = route('login.teacher');
        }

        // request ajax
        if ($request->ajax()) {
            return response()->json(['url' => $url], 401);
        }

        // regular browser request
        return redirect()->guest($url);
    }
}
