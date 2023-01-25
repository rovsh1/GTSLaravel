<?php

namespace GTS\Shared\Infrastructure\Support\Module;

class Module
{

    public function __construct(
        private readonly string $name,
        private readonly array $config = []
    ) {
    }

    public function config(string $name = null)
    {
        if (null === $name)
            return $this->config;

        $tmp = $this->config;
        foreach (explode('.', $name) as $k) {
            if (!isset($tmp[$k]))
                return null;

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
        return $this->get('path') . DIRECTORY_SEPARATOR . trim($path, DIRECTORY_SEPARATOR);
    }

    public function namespace(string $namespace = null): string
    {
        return $this->get('namespace') . '\\' . trim($namespace, '\\');
    }
}
