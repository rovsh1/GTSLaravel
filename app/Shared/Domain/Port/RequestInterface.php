<?php

namespace GTS\Shared\Domain\Port;

interface RequestInterface
{
    public function module(): string;

    public function port(): string;

    public function method(): string;

    /**
     * @return array<string, mixed>
     */
    public function arguments(): array;
}
