<?php

namespace Wotz\SwaggerUi\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class EnsureUserIsAuthorized
{
    public function handle(Request $request, Closure $next)
    {
        if (app()->environment('local')) {
            return $next($request);
        }

        if (Gate::allows('viewSwaggerUI', [$request->user()])) {
            return $next($request);
        }

        return abort(403);
    }
}
