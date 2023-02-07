<?php

namespace GTS\Shared\Custom\Foundation;

class Module
{
    private bool $loaded = false;

    public function __construct(
        private readonly string $name,
        private readonly array  $config = [],
    ) {}

    public function config(string $name = null)
    {
        if (null === $name) {
            return $this->config;
        }

        $tmp = $this->config;
        foreach (explode('.', $name) as $k) {
            if (!isset($tmp[$k])) {
                return null;
            }

            $tmp = $tmp[$k];
        }

        return $tmp;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function path(string $path = null): string
    {
        return $this->config('path') . DIRECTORY_SEPARATOR . trim($path, DIRECTORY_SEPARATOR);
    }

    public function namespace(string $namespace = null): string
    {
        return $this->config('namespace') . '\\' . trim($namespace, '\\');
    }

    public function isDeferred(): bool
    {
        return true === $this->config('deferred');
    }

    public function isLoaded(): bool
    {
        return $this->loaded;
    }

    public function loaded(): void
    {
        $this->loaded = true;
    }
}
