<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Domain\ValueObject\Details;

use Module\Booking\Common\Domain\Entity\Details\AdditionalInfoInterface;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\AdditionalInfo\ExternalNumber;

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
