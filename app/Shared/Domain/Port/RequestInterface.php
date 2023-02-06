<?php

namespace GTS\Shared\Domain\Port;

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
