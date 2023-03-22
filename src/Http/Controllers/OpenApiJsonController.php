<?php

namespace NextApps\SwaggerUi\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use RuntimeException;

class OpenApiJsonController
{
    public function __invoke(string $path, string $filename) : JsonResponse
    {
        $file = collect(config('swagger-ui.files'))->filter(function ($values) use ($filename, $path) {
            return isset($values['versions'][$filename]) && ltrim($values['path'], '/') === $path;
        })->first();

        if (empty($file)) {
            abort(404);
        }

        $json = $this->getJson($file['versions'][$filename]);

        $json = $this->configureServer($file, $json);
        $json = $this->configureOAuth($file, $json);

        return response()->json($json);
    }

    protected function getJson(string $path) : array
    {
        $content = file_get_contents($path);

        if (Str::endsWith($path, '.yaml')) {
            if (! extension_loaded('yaml')) {
                throw new RuntimeException('OpenAPI YAML file can not be parsed if the YAML extension is not loaded');
            }

            return yaml_parse($content);
        }

        return json_decode($content, true);
    }

    protected function configureServer(array $file, array $json) : array
    {
        if (! $file['modify_file']) {
            return $json;
        }

        $json['servers'] = [
            ['url' => config('app.url')],
        ];

        return $json;
    }

    protected function configureOAuth(array $file, array $json) : array
    {
        if (empty($json['components']['securitySchemes']) || ! $file['modify_file']) {
            return $json;
        }

        $securitySchemes = collect($json['components']['securitySchemes'])->map(function ($scheme) use ($file) {
            if ($scheme['type'] !== 'oauth2') {
                return $scheme;
            }

            $scheme['flows'] = collect($scheme['flows'])->map(function ($flow) use ($file) {
                if (isset($flow['tokenUrl'])) {
                    $flow['tokenUrl'] = url($file['oauth']['token_path']);
                }

                if (isset($flow['refreshUrl'])) {
                    $flow['refreshUrl'] = url($file['oauth']['refresh_path']);
                }

                if (isset($flow['authorizationUrl'])) {
                    $flow['authorizationUrl'] = url($file['oauth']['authorization_path']);
                }

                return $flow;
            });

            return $scheme;
        });

        $json['components']['securitySchemes'] = $securitySchemes->toArray();

        return $json;
    }
}
