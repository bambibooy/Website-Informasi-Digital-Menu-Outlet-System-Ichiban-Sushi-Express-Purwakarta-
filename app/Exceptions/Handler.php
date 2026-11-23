<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
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

        $this->renderable(function (\Illuminate\Session\TokenMismatchException $e, $request) {
            if ($request->expectsJson() || $request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'Sesi Anda telah kedaluwarsa atau token keamanan tidak valid. Silakan muat ulang halaman.',
                    'csrf_error' => true,
                ], 419);
            }
            return redirect()->route('admin.login')->with('error', 'Sesi Anda telah kedaluwarsa. Silakan masuk kembali.');
        });
    }
}
