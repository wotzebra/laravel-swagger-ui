<?php

namespace NextApps\SwaggerUi\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\ItemNotFoundException;

class SwaggerViewController
{
    public function __invoke(Request $request)
    {
        try {
            $file = collect(config('swagger-ui.files'))->filter(function ($values) use ($request) {
                return ltrim($values['path'], '/') === $request->path();
            })->firstOrFail();
        } catch (ItemNotFoundException $e) {
            return response()->json(['error' => 'File not found'], 404);
        }

        return view('swagger-ui::index', ['data' => collect($file)]);
    }
}
