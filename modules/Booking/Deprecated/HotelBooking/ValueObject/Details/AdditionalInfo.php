<?php

declare(strict_types=1);

namespace Module\Booking\Deprecated\HotelBooking\ValueObject\Details;

use Module\Booking\Domain\Booking\ValueObject\HotelBooking\ExternalNumber;
use Module\Booking\Domain\Shared\Entity\Details\AdditionalInfoInterface;

final class AdditionalInfo implements AdditionalInfoInterface
{
    public function __construct(
        private ?ExternalNumber $externalNumber = null
    ) {}

    public function externalNumber(): ?ExternalNumber
    {
        return $this->externalNumber;
    }

    public function setExternalNumber(ExternalNumber $externalNumber): void
    {
        $this->externalNumber = $externalNumber;
    }

    public function toData(): array
    {
        return [
            'externalNumber' => $this->externalNumber?->toData()
        ];
    }

    public static function fromData(array $data): static
    {
        $externalNumber = $data['externalNumber'] ?? null;
        return new static(
            $externalNumber !== null ? ExternalNumber::fromData($externalNumber) : null
        );
    }
}
