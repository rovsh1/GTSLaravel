<?php

namespace Module\Shared\Domain\Adapter;

interface ConstantAdapterInterface
{
    public function value(string $key): mixed;

    public function basicCalculatedValue(): float;
}