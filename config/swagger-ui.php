<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Swagger UI - Path
    |--------------------------------------------------------------------------
    |
    | This is the URI path where SwaggerUI will be accessible from. Feel free
    | to change this path to anything you like.
    |
    */

    'path' => 'swagger',

    /*
    |--------------------------------------------------------------------------
    | Swagger UI - Mode
    |--------------------------------------------------------------------------
    |
    | Choose how Swagger UI should access your OpenAPI. You can enter
    | a direct url or path to your file on disk
    |
    | Supported: "file", "url"
    |
    */

    'mode' => env('SWAGGER_UI_MODE', 'file'),

    /*
    |--------------------------------------------------------------------------
    | Swagger UI - OpenAPI File
    |--------------------------------------------------------------------------
    |
    | This is the location of the project's OpenAPI / Swagger JSON file. It's
    | this file that will be used in Swagger UI.
    |
    */

    'file' => env('SWAGGER_UI_OPENAPI_FILE', resource_path('swagger/openapi.json')),

    /*
    |--------------------------------------------------------------------------
    | Swagger UI - OpenAPI Url
    |--------------------------------------------------------------------------
    |
    | This is the location of the project's OpenAPI / Swagger JSON url. It's
    | this url that will be used in Swagger UI.
    |
    */

    'url' => env('SWAGGER_UI_OPENAPI_URL', url('openapi.json')),

    /*
    |--------------------------------------------------------------------------
    | Swagger UI - OAuth Config
    |--------------------------------------------------------------------------
    |
    | This allows you to configure oauth within Swagger UI. It makes it easier
    | to authenticate in Swagger UI by prefilling certain values.
    |
    */
    'oauth' => [
        'token_path' => 'oauth/token',
        'refresh_path' => 'oauth/token',
        'authorization_path' => 'oauth/authorize',

        'client_id' => env('SWAGGER_UI_OAUTH_CLIENT_ID'),
        'client_secret' => env('SWAGGER_UI_OAUTH_CLIENT_SECRET'),
    ],
];
