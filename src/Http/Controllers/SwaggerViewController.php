<?php

namespace Wotz\SwaggerUi\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\ItemNotFoundException;
use Illuminate\View\View;

class SwaggerViewController
{
    public function __invoke(Request $request) : View
    {
        try {
            $file = collect(config('swagger-ui.files'))->filter(function ($values) use ($request) {
                return ltrim($values['path'], '/') === $request->path();
            })->firstOrFail();
        } catch (ItemNotFoundException) {
            return abort(404);
        }

        return view('swagger-ui::index', ['data' => collect($file)]);
    }
}
