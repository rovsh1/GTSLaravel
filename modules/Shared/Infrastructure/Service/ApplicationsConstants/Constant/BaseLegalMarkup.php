<?php

namespace Module\Shared\Infrastructure\Service\ApplicationsConstants\Constant;

use Module\Shared\Infrastructure\Service\ApplicationsConstants\AbstractConstant;

final class BaseLegalMarkup extends AbstractConstant implements ConstantInterface
{
    protected string $cast = 'int';

    public function name(): string
    {
        return 'Базовая наценка для юр.лиц';
    }

    public function default(): int
    {
        return 3;
    }
}
