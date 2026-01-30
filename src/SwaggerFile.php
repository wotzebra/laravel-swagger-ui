<?php

namespace Wotz\SwaggerUi;

use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use RuntimeException;

class SwaggerFile
{
    protected ?array $json;

    public function __construct(
        protected readonly string $name,
        protected readonly string $version,
    ) {
    }

    public static function make(string $name, string $version) : self
    {
        return new self($name, $version);
    }

    public function exists() : bool
    {
        return $this->getConfig("versions.{$this->version}") !== null;
    }

    public function doesntExist() : bool
    {
        return ! $this->exists();
    }

    public function collect(?string $key = null) : Collection
    {
        return collect($this->json($key));
    }

    public function json(?string $key = null) : mixed
    {
        if ($key !== null) {
            return data_get($this->json(), $key);
        }

        if (isset($this->json)) {
            return $this->json;
        }

        $path = $this->getConfig("versions.{$this->version}");

        if ($path === null) {
            throw new RuntimeException("OpenAPI version '{$this->version}' not found for file '{$this->name}'");
        }

        try {
            $content = file_get_contents($path);
        } catch (Exception $e) {
            throw new RuntimeException('OpenAPI file can not be read');
        }

        if (Str::endsWith($path, '.yaml')) {
            if (! extension_loaded('yaml')) {
                throw new RuntimeException('OpenAPI YAML file can not be parsed if the YAML extension is not loaded');
            }

            $this->json = yaml_parse($content);
        } else {
            $this->json = json_decode($content, true);
        }

        $this->json = $this->configureServer($this->json);
        $this->json = $this->configureOAuth($this->json);

        return $this->json;
    }

    protected function configureServer(array $json) : array
    {
        if (! $this->getConfig('modify_file')) {
            return $json;
        }

        $serverUrl = $this->getConfig('server_url', config('app.url'));

        if ($this->getConfig('append_version_to_server_url', false)) {
            $serverUrl = rtrim($serverUrl, '/') . '/' . $this->version;
        }

        $json['servers'] = [
            [
                'url' => $serverUrl,
                'variables' => $this->getConfig('server_variables', []),
            ],
        ];

        return $json;
    }

    protected function configureOAuth(array $json) : array
    {
        if (empty($json['components']['securitySchemes']) || ! $this->getConfig('modify_file')) {
            return $json;
        }

        $securitySchemes = collect($json['components']['securitySchemes'])->map(function ($scheme) {
            if ($scheme['type'] !== 'oauth2') {
                return $scheme;
            }

            $scheme['flows'] = collect($scheme['flows'])->map(function ($flow) {
                if (isset($flow['tokenUrl'])) {
                    $flow['tokenUrl'] = url($this->getConfig('oauth.token_path'));
                }

                if (isset($flow['refreshUrl'])) {
                    $flow['refreshUrl'] = url($this->getConfig('oauth.refresh_path'));
                }

                if (isset($flow['authorizationUrl'])) {
                    $flow['authorizationUrl'] = url($this->getConfig('oauth.authorization_path'));
                }

                return $flow;
            })->toArray();

            return $scheme;
        });

        $json['components']['securitySchemes'] = $securitySchemes->toArray();

        return $json;
    }

    protected function getConfig(?string $key = null, $default = null) : mixed
    {
        $config = collect(config('swagger-ui.files'))->firstWhere('path', $this->name);

        if ($key === null) {
            return $config;
        }

        return data_get($config, $key) ?? $default;
    }
}
