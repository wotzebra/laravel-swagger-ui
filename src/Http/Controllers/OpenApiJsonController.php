<?php

namespace NextApps\SwaggerUi\Http\Controllers;

class OpenApiJsonController
{
    /**
     * Get, prepare and return the OpenAPI / Swagger JSON file.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        $json = $this->getJson();

        if (config('swagger-ui.local_server_only')) {
            $json = $this->configureServer($json);
        }
        $json = $this->configureOAuth($json);

        return response()->json($json);
    }

    /**
     * Get the OpenAPI json.
     *
     * @return array
     */
    protected function getJson()
    {
        $path = config('swagger-ui.file');

        return json_decode(file_get_contents($path), true);
    }

    /**
     * Configure the server in OpenAPI JSON, based on current environment.
     *
     * @param array $json
     *
     * @return array
     */
    protected function configureServer(array $json)
    {
        $json['servers'] = [
            ['url' => config('app.url').config('swagger-ui.api_base_path')],
        ];

        return $json;
    }

    /**
     * Configure the oauth token url.
     *
     * @param array $json
     *
     * @return array
     */
    protected function configureOAuth(array $json)
    {
        if (empty($json['components']['securitySchemes'])) {
            return $json;
        }

        $json['components']['securitySchemes'] = collect($json['components']['securitySchemes'])->map(function ($scheme) {
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

        return $json;
    }
}
