<?php

declare(strict_types=1);

namespace Module\Booking\Tourist\Domain\Repository;

use Module\Booking\Tourist\Domain\Tourist;
use Module\Shared\Domain\ValueObject\GenderEnum;

interface TouristRepositoryInterface
{
    public function create(string $fullName, int $countryId, GenderEnum $gender, bool $isAdult, ?int $age): Tourist;
}
