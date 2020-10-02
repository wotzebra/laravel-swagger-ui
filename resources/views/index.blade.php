<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name') }} - Swagger</title>

        <link rel="stylesheet" type="text/css" href="https://unpkg.com/swagger-ui-dist@latest/swagger-ui.css">
        <style>
            html {
                box-sizing: border-box;
            }

            *, *:before, *:after {
                box-sizing: inherit;
            }

            body {
                margin:0;
                background: #fafafa;
            }
        </style>

        <script src="https://unpkg.com/swagger-ui-dist@latest/swagger-ui-standalone-preset.js"></script>
        <script src="https://unpkg.com/swagger-ui-dist@latest/swagger-ui-bundle.js"></script>

        <script>
            window.onload = function () {
                window.ui = SwaggerUIBundle({
                    url: '{{ route('swagger-openapi-json', [], false) }}',
                    dom_id: '#swagger-ui',
                    deepLinking: true,
                    presets: [
                        SwaggerUIBundle.presets.apis,
                        SwaggerUIStandalonePreset,
                    ],
                    plugins: [
                        SwaggerUIBundle.plugins.DownloadUrl,
                    ],
                    layout: 'StandaloneLayout',
                });

                ui.initOAuth({
                    clientId: '{{ config('swagger-ui.oauth.client_id') }}',
                    clientSecret: '{{ config('swagger-ui.oauth.client_secret') }}',
                });
            };
        </script>
    </head>
    <body>
        <div id="swagger-ui"></div>
    </body>
</html>
