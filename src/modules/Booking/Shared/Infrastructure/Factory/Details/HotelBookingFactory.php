<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Factory\Details;

use Module\Booking\Shared\Domain\Booking\Entity\HotelBooking;
use Module\Booking\Shared\Domain\Booking\Factory\Details\HotelBookingFactoryInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\BookingPeriod;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\HotelInfo;
use Module\Booking\Shared\Infrastructure\Builder\Details\HotelBookingBuilder;
use Module\Booking\Shared\Infrastructure\Models\Details\Hotel;
use Module\Shared\Enum\Booking\QuotaProcessingMethodEnum;

class HotelBookingFactory implements HotelBookingFactoryInterface
{
    public function __construct(private readonly HotelBookingBuilder $builder)
    {
    }

    public function create(
        BookingId $bookingId,
        HotelInfo $hotelInfo,
        BookingPeriod $bookingPeriod,
        QuotaProcessingMethodEnum $quotaProcessingMethod,
    ): HotelBooking {
        $model = Hotel::create([
            'booking_id' => $bookingId->value(),
            'hotel_id' => $hotelInfo->id(),
            'date_start' => $bookingPeriod->dateFrom(),
            'date_end' => $bookingPeriod->dateTo(),
            'nights_count' => $bookingPeriod->nightsCount(),
            'quota_processing_method' => $quotaProcessingMethod,
            'data' => [
                'hotelInfo' => $hotelInfo->toData(),
                'period' => $bookingPeriod->toData(),
            ]
        ]);

        return $this->builder->build(Hotel::find($model->id));
    }
}
