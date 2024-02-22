<?php

declare(strict_types=1);

namespace Sdk\Shared\Enum\Client;

enum ResidencyEnum: int
{
    case RESIDENT = 1;
    case NONRESIDENT = 2;
    case ALL = 3;

    public function hasResidencyFlag(bool $isResident): bool
    {
        if ($this === ResidencyEnum::ALL) {
            return true;
        }

        return $isResident
            ? $this === ResidencyEnum::RESIDENT
            : $this === ResidencyEnum::NONRESIDENT;
    }
}
