<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Support\Facades\Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final readonly class RoleMiddleware
{
    /**
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     *
     * @throws \Exception
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (! auth()->check()) {
            abort(401);
        }

        if (! Auth::findOrFail()->roles()->where('name', $role)->exists()) {
            abort(403);
        }

        return $next($request);
    }
}
