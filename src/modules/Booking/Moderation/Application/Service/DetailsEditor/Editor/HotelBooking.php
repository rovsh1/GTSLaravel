<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Service\DetailsEditor\Editor;

use Module\Booking\Shared\Domain\Booking\Adapter\HotelAdapterInterface;
use Module\Booking\Shared\Domain\Booking\Entity\HotelBooking as Entity;
use Module\Booking\Shared\Domain\Booking\Factory\Details\HotelBookingFactoryInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\BookingPeriod;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\HotelInfo;
use Module\Booking\Shared\Domain\Booking\ValueObject\ServiceId;
use Module\Shared\Enum\Booking\QuotaProcessingMethodEnum;
use Module\Shared\ValueObject\Time;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class HotelBooking extends AbstractEditor implements EditorInterface
{
    public function __construct(
        private readonly HotelBookingFactoryInterface $detailsFactory,
        private readonly HotelAdapterInterface $hotelAdapter,
    ) {
    }

    public function create(BookingId $bookingId, ServiceId $serviceId, array $detailsData): Entity
    {
        $hotelDto = $this->hotelAdapter->findById($serviceId->value());
        if ($hotelDto === null) {
            throw new EntityNotFoundException('Hotel not found');
        }
        $hotelInfo = new HotelInfo(
            $hotelDto->id,
            $hotelDto->name,
            new Time($hotelDto->timeSettings->checkInAfter),
            new Time($hotelDto->timeSettings->checkOutBefore),
        );

        $bookingPeriod = BookingPeriod::fromCarbon($detailsData['period']);

        return $this->detailsFactory->create(
            $bookingId,
            $hotelInfo,
            $bookingPeriod,
            QuotaProcessingMethodEnum::from((int)$detailsData['quota_processing_method']),
        );
    }
}
