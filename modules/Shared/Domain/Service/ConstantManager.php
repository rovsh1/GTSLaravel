<?php

declare(strict_types=1);

namespace Module\Shared\Domain\Service;

use Module\Shared\Domain\Repository\ConstantRepositoryInterface;

class ConstantManager
{
    public function __construct(
        private readonly ConstantRepositoryInterface $constantRepository
    ) {
    }

    public function value(string $key): mixed
    {
        return $this->constantRepository->getConstantValue($key);
    }
}
