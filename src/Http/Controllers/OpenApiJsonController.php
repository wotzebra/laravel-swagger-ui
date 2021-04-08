<?php

namespace jamesRUS52\Laravel\SwaggerUi\Http\Controllers;

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

        $json = $this->configureServer($json);
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
        if (config('swagger-ui.servers') === null) {
            return $json;
        }

        $json['servers'] = collect(config('swagger-ui.servers'))->map(fn ($url) => ['url' => $url]);

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
                    $flow['tokenUrl'] = config('swagger-ui.oauth.token_url');
                }

                if (isset($flow['refreshUrl'])) {
                    $flow['refreshUrl'] = config('swagger-ui.oauth.refresh_url');
                }

                if (isset($flow['authorizationUrl'])) {
                    $flow['authorizationUrl'] = config('swagger-ui.oauth.authorization_url');
                }

                return $flow;
            });

            return $scheme;
        });

        return $json;
    }
}
