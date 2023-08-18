<?php

namespace Module\Shared\Application\Entity\Constant;

interface ConstantInterface
{
    public function key(): string;

    public function value(): mixed;

    public function default(): mixed;

    public function cast(): string;
}
