<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function handle(Request $request, Closure $next, $permission): Response
    {
        if (!$request->user()) {
            return redirect('login');
        }

        if (!$request->user()->can($permission)) {
            abort(403, 'Vous n\'avez pas la permission d\'effectuer cette action.');
        }

        return $next($request);
    }
}
