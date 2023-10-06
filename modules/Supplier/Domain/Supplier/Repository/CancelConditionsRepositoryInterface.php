<?php

declare(strict_types=1);

namespace Module\Supplier\Domain\Supplier\Repository;

use Module\Supplier\Domain\Supplier\ValueObject\CancelConditions;

interface CancelConditionsRepositoryInterface
{
    public function get(): CancelConditions;

    public function store(CancelConditions $cancelConditions): bool;
}
