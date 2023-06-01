<?php

declare(strict_types=1);

namespace Module\Booking\Common\Domain\Entity\Details;

use Illuminate\Support\Collection;
use Module\Booking\Hotel\Domain\ValueObject\Details\BookingPeriod;
use Module\Booking\Hotel\Domain\ValueObject\Details\CancelConditions;
use Module\Shared\Domain\Entity\EntityInterface;

interface HotelDetailsInterface extends BookingDetailsInterface
{
    public function hotelId(): int;

    public function period(): BookingPeriod;

    public function additionalInfo(): ?AdditionalInfoInterface;

    public function rooms(): Collection;

    public function cancelConditions(): CancelConditions;
}
