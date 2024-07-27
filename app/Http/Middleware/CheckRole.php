<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Check user role
        //dd($roles);
        $user = $request->user();

        if ($request->user()->hasRoles($roles)) {
            return $next($request);
        }

        // If user does not have the required role, show 403 error
        abort(403, 'Unauthorized access.');
    }
}
