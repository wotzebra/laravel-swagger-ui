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
        $json = file_get_contents(config('swagger-ui.file'));
        $json = json_decode($json, true);

        $json['schemes'] = [parse_url(config('app.url'), PHP_URL_SCHEME)];
        $json['host'] = str_replace($json['schemes'][0], '', config('app.url'));

        return response()->json($json);
    }
}
