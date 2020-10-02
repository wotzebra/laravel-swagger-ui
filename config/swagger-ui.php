<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Swagger UI Path
    |--------------------------------------------------------------------------
    |
    | This is the URI path where SwaggerUI will be accessible from. Feel free
    | to change this path to anything you like. Note that the URI will not
    | affect the paths of its internal API that aren't exposed to users.
    |
    */

    'path' => 'swagger',

    /*
    |--------------------------------------------------------------------------
    | Swagger UI - OpenAPI File
    |--------------------------------------------------------------------------
    |
    */

    'file' => resource_path('swagger/openapi.json'),

    'oauth' => [
        'client_id' => env('SWAGGER_UI_OAUTH_CLIENT_ID'),
        'client_secret' => env('SWAGGER_UI_OAUTH_CLIENT_SECRET'),
    ]
];
