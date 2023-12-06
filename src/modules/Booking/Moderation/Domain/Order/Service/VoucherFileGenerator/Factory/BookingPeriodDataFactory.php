<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Domain\Order\Service\VoucherFileGenerator\Factory;

use Module\Booking\Moderation\Domain\Order\Service\VoucherFileGenerator\Dto\Service\BookingPeriodDto;
use Sdk\Booking\Contracts\Entity\DetailsInterface;

class BookingPeriodDataFactory
{
    public function build(DetailsInterface $details): BookingPeriodDto
    {
        if (method_exists($details, 'bookingPeriod')) {
            $bookingPeriod = $details->bookingPeriod();
        } else {
            return $this->buildBookingPeriodFromDate($details->serviceDate());
        }

        if (method_exists($bookingPeriod, 'nightsCount')) {
            $daysCount = $bookingPeriod->nightsCount();
        } else {
            $daysCount = $bookingPeriod->daysCount();
        }

        return new BookingPeriodDto(
            $bookingPeriod->dateFrom()->format('d.m.Y'),
            $bookingPeriod->dateFrom()->format('H:i'),
            $bookingPeriod->dateTo()->format('d.m.Y'),
            $bookingPeriod->dateTo()->format('H:i'),
            $daysCount,
        );
    }

    private function buildBookingPeriodFromDate(\DateTimeInterface $date): BookingPeriodDto
    {
        return new BookingPeriodDto(
            $date->format('d.m.Y'),
            $date->format('H:i'),
            null,
            null,
            null,
        );
    }
}
