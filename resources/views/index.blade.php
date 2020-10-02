<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" type="text/css" href="https://unpkg.com/swagger-ui-dist@3.12.1/swagger-ui.css">

        <script src="https://unpkg.com/swagger-ui-dist@3.12.1/swagger-ui-standalone-preset.js"></script>
        <script src="https://unpkg.com/swagger-ui-dist@3.12.1/swagger-ui-bundle.js"></script>

        <script>
            window.onload = function () {
                window.ui = SwaggerUIBundle({
                    url: '{{ route('swagger-openapi-json', [], false) }}',
                    dom_id: '#swagger-ui',
                    deepLinking: true,
                    presets: [
                        SwaggerUIBundle.presets.apis,
                        SwaggerUIStandalonePreset
                    ],
                    plugins: [
                        SwaggerUIBundle.plugins.DownloadUrl
                    ],
                    layout: "StandaloneLayout",
                })

                ui.initOAuth({
                    clientId: '{{ config('swagger-ui.oauth.client_id') }}',
                    clientSecret: '{{ config('swagger-ui.oauth.client_secret') }}',
                })
            };
        </script>
    </head>
    <body>
        <div id="swagger-ui"></div>
    </body>
</html>
