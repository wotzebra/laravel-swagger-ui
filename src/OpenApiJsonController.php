<?php

namespace NextApps\SwaggerUI;

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

        $json = $this->configureHost($json);
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
     * Configure the host and schemes in OpenAPI JSON, based on current environment.
     *
     * @param array $json
     *
     * @return array
     */
    protected function configureHost(array $json)
    {
        $json['schemes'] = [parse_url(config('app.url'), PHP_URL_SCHEME)];
        $json['host'] = str_replace("{$json['schemes'][0]}://", '', config('app.url'));

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
        $json['securityDefinitions'] = collect($json['securityDefinitions'])->map(function ($definition) {
            if ($definition['type'] === 'oauth2') {
                $definition['tokenUrl'] = url(config('swagger-ui.oauth.path'));
            }

            return $definition;
        });

        return $json;
    }
}
