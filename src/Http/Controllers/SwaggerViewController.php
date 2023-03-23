<?php

namespace NextApps\SwaggerUi\Http\Controllers;

use Illuminate\Http\Request;

class SwaggerViewController
{
    public function __invoke(Request $request)
    {
        $file = collect(config('swagger-ui.files'))->filter(function ($values) use ($request) {
            return ltrim($values['path'], '/') === $request->path();
        })->firstOrFail();

        return view('swagger-ui::index', ['data' => collect($file)]);
    }
}
