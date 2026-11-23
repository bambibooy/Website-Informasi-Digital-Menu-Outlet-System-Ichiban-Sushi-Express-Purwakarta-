<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Login admin.
     * Validasi spesifik (username/email tidak ditemukan, password salah, akses bukan admin)
     * Terlindungi dari SQL Injection (Eloquent PDO parameter binding) & password hashing (Bcrypt).
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string'],
            'email'    => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ], [
            'username.required' => 'Username wajib diisi.',
            'email.required'    => 'Alamat Email wajib diisi.',
            'email.email'       => 'Format alamat email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $usernameInput = trim($request->input('username', ''));
        $emailInput    = trim($request->input('email', ''));

        // Validasi: Username dan Alamat Email tidak boleh diisi dengan nilai yang sama
        if (strcasecmp($usernameInput, $emailInput) === 0) {
            return $this->respondError($request, 'email', 'Username dan Alamat Email tidak boleh diisi dengan nilai yang sama.');
        }

        // Cari data pengguna di database berdasarkan username
        $user = User::where('username', $usernameInput)->first();
        if (!$user) {
            return $this->respondError($request, 'username', 'Username tidak ditemukan.');
        }

        // Verifikasi kecocokan alamat email dengan akun tersebut
        if (strcasecmp($user->email, $emailInput) !== 0) {
            return $this->respondError($request, 'email', 'Alamat Email tidak cocok dengan akun username tersebut.');
        }

        // Verifikasi password dengan Hash::check
        if (!Hash::check($request->input('password'), $user->password)) {
            return $this->respondError($request, 'password', 'Password salah. Silakan coba lagi.');
        }

        // Verifikasi role Administrator
        if (!$user->isAdmin()) {
            return $this->respondError($request, 'role', 'Akses ditolak. Akun Anda bukan Administrator.', 403);
        }

        // Login sukses: buat sesi dan regenerasi session ID untuk cegah session fixation
        $remember = $request->boolean('remember', true);
        Auth::login($user, $remember);
        $request->session()->regenerate();

        if ($request->expectsJson() || $request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success'  => true,
                'name'     => $user->username ?? $user->name,
                'username' => $user->username ?? $user->name,
                'email'    => $user->email,
                'role'     => $user->role ?? 'Admin',
                'redirect' => route('admin.dashboard'),
            ]);
        }

        return redirect()->intended(route('admin.dashboard'));
    }

    /**
     * Format respon error terpadu (JSON atau redirect back dengan error).
     */
    private function respondError(Request $request, string $field, string $message, int $status = 422)
    {
        if ($request->expectsJson() || $request->wantsJson() || $request->ajax()) {
            return response()->json([
                'message' => $message,
                'errors'  => [$field => [$message]],
            ], $status);
        }

        return back()->withErrors([$field => $message])->withInput();
    }

    /**
     * Logout: Hapus sesi + cookie dan alihkan kembali ke halaman login.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->expectsJson() || $request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success'  => true,
                'message'  => 'Berhasil keluar.',
                'redirect' => route('admin.login'),
            ]);
        }

        return redirect()->route('admin.login')->with('success', 'Anda telah berhasil keluar dari sistem.');
    }
}
