<?php

namespace GTS\Shared\Custom\Foundation;

class Module
{
    private const PORT_INTERFACE_REGEX = '/([a-zA-Z]+)PortInterface\.php/m';
    private bool $loaded = false;

    public function __construct(
        private readonly string $name,
        private readonly array  $config = [],
        private ?array          $ports = null
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

    public function portNamespace(string $portName)
    {
        return $this->namespace("Infrastructure\\Port\\{$portName}PortInterface");
    }

    /**
     * @return string[]
     */
    public function availablePorts(): array
    {
        if ($this->ports === null) {
            $files = scandir($this->portsPath());
            $this->ports = array_filter(array_map(function (string $fileName) {
                if (!preg_match(self::PORT_INTERFACE_REGEX, $fileName, $matches)) {
                    return null;
                }
                return $matches[1];
            }, $files));
        }
        return $this->ports;
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

    public function manifestPath(): string
    {
        return $this->path('manifest.json');
    }

    private function portsPath(): string
    {
        return $this->path('Infrastructure/Port');
    }
}
