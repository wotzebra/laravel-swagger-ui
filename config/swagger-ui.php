<?php

use Wotz\SwaggerUi\Http\Middleware\EnsureUserIsAuthorized;

return [
    'files' => [
        [
            /*
             * The path where the swagger file is served.
             */
            'path' => 'swagger',

            /*
             * The title of the page where the swagger file is served.
             */
            'title' => env('APP_NAME') . ' - Swagger',

            /*
             * The versions of the swagger file. The key is the version name and the value is the path to the file.
             */
            'versions' => [
                'v1' => resource_path('swagger/openapi.json'),
            ],

            /*
             * The default version that is loaded when the route is accessed.
             */
            'default' => 'v1',

            /*
             * The middleware that is applied to the route.
             */
            'middleware' => [
                'web',
                EnsureUserIsAuthorized::class,
            ],

            /*
             * Specify the validator URL. Set to false to disable validation.
             */
            'validator_url' => env('SWAGGER_UI_VALIDATOR_URL'),

            /*
             * If enabled the file will be modified to set the server url and oauth urls.
             */
            'modify_file' => true,

            /*
             * The oauth configuration for the swagger file.
             */
            'oauth' => [
                'token_path' => 'oauth/token',
                'refresh_path' => 'oauth/token',
                'authorization_path' => 'oauth/authorize',

                'client_id' => env('SWAGGER_UI_OAUTH_CLIENT_ID'),
                'client_secret' => env('SWAGGER_UI_OAUTH_CLIENT_SECRET'),
            ],

            /*
             * Path to a custom stylesheet file if you want to customize the look and feel of swagger-ui.
             * The content of the file will be read and added into a style-tag on the swagger-ui page.
             */
            'stylesheet' => null,
        ],
    ],
];
