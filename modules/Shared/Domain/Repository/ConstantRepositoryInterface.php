<?php

namespace Module\Shared\Domain\Repository;

use Module\Shared\Domain\Constant\ConstantInterface;

interface ConstantRepositoryInterface
{
    public function findByKey(string $key): ConstantInterface;

    public function getConstantValue(string $key): mixed;
}
