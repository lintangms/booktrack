<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    use ApiResponse;

    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user() ?: auth('api')->user();

        if (!$user) {
            if ($request->is('api/*')) {
                return $this->error('Token tidak valid atau tidak ada', 401);
            }
            return redirect()->route('login');
        }

        $allowedRoles = array_map('strtolower', $roles);
        if (!in_array(strtolower($user->role), $allowedRoles, true)) {
            if ($request->is('api/*')) {
                return $this->error('Anda tidak memiliki akses ke sumber daya ini', 403);
            }
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}
