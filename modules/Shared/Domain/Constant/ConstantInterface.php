<?php

namespace Module\Shared\Domain\Constant;

interface ConstantInterface
{
    public function key(): string;

    public function value(): mixed;
}
