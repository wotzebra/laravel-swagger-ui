<?php

namespace NextApps\SwaggerUi\Http\Middleware;

use Illuminate\Support\Facades\Gate;

class EnsureUserIsAuthorized
{
    /**
     * Ensures the user is authorized to visit Swagger UI.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     *
     * @return \Illuminate\Http\Response
     */
    public function handle($request, $next)
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
