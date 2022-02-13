<?php

namespace NextApps\SwaggerUi\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use RuntimeException;

class OpenApiJsonController
{
    public function __invoke() : JsonResponse
    {
        $json = $this->getJson();

        $json = $this->configureServer($json);
        $json = $this->configureOAuth($json);

        return response()->json($json);
    }

    protected function getJson() : array
    {
        $path = config('swagger-ui.file');

        if (!filter_var($path, FILTER_VALIDATE_URL) && Str::endsWith($path, '.yaml')) {
            if (! extension_loaded('yaml')) {
                throw new RuntimeException('OpenAPI YAML file can not be parsed if the YAML extension is not loaded');
            }

            return yaml_parse_file($path);
        }

        return json_decode(file_get_contents($path), true);
    }

    protected function configureServer(array $json) : array
    {
        if(!isset($json['servers'])) {
            $json['servers'] = [
                ['url' => config('app.url')],
            ];
        }

        return $json;
    }

    protected function configureOAuth(array $json) : array
    {
        if (empty($json['components']['securitySchemes'])) {
            return $json;
        }

        $securitySchemes = collect($json['components']['securitySchemes'])->map(function ($scheme) {
            if ($scheme['type'] !== 'oauth2') {
                return $scheme;
            }

            $scheme['flows'] = collect($scheme['flows'])->map(function ($flow) {
                if (isset($flow['tokenUrl'])) {
                    $flow['tokenUrl'] = url(config('swagger-ui.oauth.token_path'));
                }

                if (isset($flow['refreshUrl'])) {
                    $flow['refreshUrl'] = url(config('swagger-ui.oauth.refresh_path'));
                }

                if (isset($flow['authorizationUrl'])) {
                    $flow['authorizationUrl'] = url(config('swagger-ui.oauth.authorization_path'));
                }

                return $flow;
            });

            return $scheme;
        });

        $json['components']['securitySchemes'] = $securitySchemes->toArray();

        return $json;
    }
}
