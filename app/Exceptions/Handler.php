<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

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

        // Handle database integrity constraint violations
        $this->renderable(function (QueryException $e, Request $request) {
            // Check if it's a duplicate entry error
            if ($e->getCode() == 23000) {
                $errorMessage = $e->getMessage();
                
                // Check for phone number duplicate
                if (strpos($errorMessage, 'users_phone_unique') !== false) {
                    if ($request->expectsJson()) {
                        return response()->json([
                            'message' => 'Số điện thoại đã được sử dụng bởi tài khoản khác.',
                            'errors' => [
                                'phone' => ['Số điện thoại đã được sử dụng bởi tài khoản khác.']
                            ]
                        ], 422);
                    }
                    
                    return back()->withErrors(['phone' => 'Số điện thoại đã được sử dụng bởi tài khoản khác.'])->withInput();
                }
                
                // Check for email duplicate
                if (strpos($errorMessage, 'users_email_unique') !== false) {
                    if ($request->expectsJson()) {
                        return response()->json([
                            'message' => 'Email đã được sử dụng bởi tài khoản khác.',
                            'errors' => [
                                'email' => ['Email đã được sử dụng bởi tài khoản khác.']
                            ]
                        ], 422);
                    }
                    
                    return back()->withErrors(['email' => 'Email đã được sử dụng bởi tài khoản khác.'])->withInput();
                }
            }
        });
    }
}
