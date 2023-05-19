<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Domain\ValueObject\Details;

use Module\Booking\Common\Domain\Entity\Details\AdditionalInfoInterface;

final class AdditionalInfo implements AdditionalInfoInterface
{
    public function toData(): array
    {
        return [];
    }

    public static function fromData(array $data): static
    {
        return new static();
    }
}
