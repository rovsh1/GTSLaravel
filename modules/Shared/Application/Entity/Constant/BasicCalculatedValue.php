<?php

namespace Module\Shared\Application\Entity\Constant;

use Module\Shared\Application\Support\Constant\AbstractConstant;

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
