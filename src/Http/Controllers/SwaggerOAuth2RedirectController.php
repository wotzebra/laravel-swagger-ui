<?php

namespace Wotz\SwaggerUi\Http\Controllers;

use Illuminate\Support\Facades\Http;

class SwaggerOAuth2RedirectController
{
    public function __invoke() : string
    {
        return Http::get('https://unpkg.com/swagger-ui-dist@latest/oauth2-redirect.html')->body();
    }
}
