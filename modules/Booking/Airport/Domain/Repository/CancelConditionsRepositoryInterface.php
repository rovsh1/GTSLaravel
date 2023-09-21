<?php

declare(strict_types=1);

namespace Module\Booking\Airport\Domain\Repository;

use Module\Booking\Common\Domain\ValueObject\CancelConditions;

interface CancelConditionsRepositoryInterface
{
    public function get(): CancelConditions;

    public function store(CancelConditions $cancelConditions): bool;
}
