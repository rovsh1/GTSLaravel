<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\HotelBooking\UseCase;

use Module\Booking\Application\Admin\HotelBooking\Builder\CalculateHotelPriceRequestDtoBuilder;
use Module\Booking\Domain\Shared\ValueObject\BookingId;
use Module\Pricing\Application\UseCase\CalculateHotelRoomsPrice;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class CalculatePrice implements UseCaseInterface
{
    public function __construct(
        private readonly CalculateHotelPriceRequestDtoBuilder $calculateHotelPriceRequestDtoBuilder,
    ) {
    }

    public function execute(int $bookingId): void
    {
        $r = app(CalculateHotelRoomsPrice::class)->execute(
            $this->calculateHotelPriceRequestDtoBuilder
                ->booking(new BookingId($bookingId))
                ->build()
        );

        dd($r);
    }
}
