<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Cek apakah pengguna sudah login
        if (!Auth::check()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'Sesi tidak valid. Silakan login terlebih dahulu.'
                ], 401);
            }

            return redirect()->route('admin.login')->with('error', 'Silakan login terlebih dahulu untuk mengakses panel admin.');
        }

        // 2. Cek apakah pengguna memiliki role sebagai Administrator
        if (!Auth::user()->isAdmin()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'Akses ditolak. Anda bukan Administrator.'
                ], 403);
            }

            return redirect()->route('admin.login')->with('error', 'Akses ditolak. Akun Anda bukan Administrator.');
        }

        return $next($request);
    }
}
