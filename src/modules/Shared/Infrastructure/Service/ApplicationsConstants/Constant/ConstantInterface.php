<?php

namespace Module\Shared\Infrastructure\Service\ApplicationsConstants\Constant;

interface ConstantInterface
{
    public function key(): string;

    public function value(): mixed;

    public function default(): mixed;

    public function cast(): string;
}