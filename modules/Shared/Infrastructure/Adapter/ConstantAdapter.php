<?php

namespace Module\Shared\Infrastructure\Adapter;

use Module\Shared\Domain\Adapter\ConstantAdapterInterface;
use Module\Shared\Domain\Repository\ConstantRepositoryInterface;

class ConstantAdapter implements ConstantAdapterInterface
{
    public function __construct(
        private readonly ConstantRepositoryInterface $constantRepository
    ) {
    }

    public function value(string $key): mixed
    {
        return $this->constantRepository->getConstantValue($key);
    }

    public function basicCalculatedValue(): float
    {
        return (float)$this->constantRepository->getConstantValue('WageRateMin');
    }
}