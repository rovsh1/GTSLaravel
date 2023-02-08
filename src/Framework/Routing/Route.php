<?php

namespace Custom\Framework\Routing;

class Route
{
    public static function fromPath($path, $action): Route
    {
        return new self($path, $action);
    }

    public function __construct(private readonly string $path, private $action) {}

    public function path(): string
    {
        return $this->path;
    }

    public function action()
    {
        return $this->action;
    }
}
