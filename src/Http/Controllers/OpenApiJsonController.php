<?php

namespace Wotz\SwaggerUi\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\ItemNotFoundException;
use Wotz\SwaggerUi\SwaggerFile;

class OpenApiJsonController
{
    public function __invoke(Request $request, string $version) : JsonResponse
    {
        $path = implode('/', array_slice($request->segments(), 0, -1));

        try {
            $config = collect(config('swagger-ui.files'))->filter(function ($values) use ($version, $path) {
                return isset($values['versions'][$version]) && ltrim($values['path'], '/') === $path;
            })->firstOrFail();
        } catch (ItemNotFoundException) {
            return abort(404);
        }

        $file = SwaggerFile::make($config['path'], $version);

        if ($file->doesntExist()) {
            return abort(404);
        }

        return response()->json($file->json());
    }
}
