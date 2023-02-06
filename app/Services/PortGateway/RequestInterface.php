<?php

namespace GTS\Services\PortGateway;

interface RequestInterface
{
    public function module(): string;

    public function port(): string;

    public function method(): string;

    /**
     * @return array<string, string>
     */
    public function arguments(): array;
}
