<?php

use NextApps\SwaggerUi\Http\Middleware\EnsureUserIsAuthorized;

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
    | Swagger UI - Route Middleware
    |--------------------------------------------------------------------------
    |
    | These middleware will be assigned to every Swagger UI route.
    |
    */

    'middleware' => [
        'web',
        EnsureUserIsAuthorized::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Swagger UI - OpenAPI File
    |--------------------------------------------------------------------------
    |
    | This is the location of the project's OpenAPI / Swagger JSON file. It's
    | this file that will be used in Swagger UI. This can either be a local
    | file or an url to a file.
    |
    */

    'file' => resource_path('swagger/openapi.json'),

    /*
    |--------------------------------------------------------------------------
    | Swagger UI - Modify File
    |--------------------------------------------------------------------------
    |
    | If this option is enabled, then the file will be changed before it is
    | used by Swagger UI. The server url and oauth urls will be changed to
    | the base url of this Laravel application.
    |
    */

    'modify_file' => true,

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
