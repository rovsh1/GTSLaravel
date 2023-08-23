<?php

namespace Module\Shared\Infrastructure\Service\ApplicationsConstants\Constant;

use Module\Shared\Infrastructure\Service\ApplicationsConstants\AbstractConstant;

final class BasicCalculatedValue extends AbstractConstant implements ConstantInterface
{
    protected string $cast = 'int';

    public function name(): string
    {
        return 'Базовая расчетная величина';
    }

    public function default(): int
    {
        return 300000;
    }
}
