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
    | Swagger UI - OpenAPI File
    |--------------------------------------------------------------------------
    |
    | This is the location of the project's OpenAPI / Swagger JSON file. It's
    | this file that will be used in Swagger UI.
    |
    */

    'file' => resource_path('swagger/openapi.json'),

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

    /*
    |--------------------------------------------------------------------------
    | Swagger UI - Current Server
    |--------------------------------------------------------------------------
    |
    | This allows you to use all servers url in your openapi.json file (Not only local server)
    | Set this option to false, to use list of servers from openapi.json file
    |
    */
    'local_server_only' => env('SWAGGER_LOCAL_SERVER_ONLY', true),

    /*
    |--------------------------------------------------------------------------
    | Swagger UI - Api base path
    |--------------------------------------------------------------------------
    |
    | This allows you to set suffix to you APP_URL
    | Set this option to /api/v1, to send your requests to APP_URL/api/v1/... endpoints
    |
    */
    'api_base_path' => env('SWAGGER_API_BASE_PATH'),
];
