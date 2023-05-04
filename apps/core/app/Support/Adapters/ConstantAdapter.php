<?php

namespace App\Core\Support\Adapters;

use Module\Shared\Domain\Service\ConstantManager;

class ConstantAdapter
{
    public function __construct(
        private readonly ConstantManager $constantManager
    ) {
    }

    public function value(string $key)
    {
        return $this->constantManager->value($key);
    }
}