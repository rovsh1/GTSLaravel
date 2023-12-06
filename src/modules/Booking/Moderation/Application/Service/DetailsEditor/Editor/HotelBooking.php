<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Service\DetailsEditor\Editor;

use Module\Booking\Shared\Domain\Booking\Adapter\HotelAdapterInterface;
use Module\Booking\Shared\Domain\Booking\Factory\Details\HotelBookingFactoryInterface;
use Sdk\Booking\Entity\Details\HotelBooking as Entity;
use Sdk\Booking\Enum\QuotaProcessingMethodEnum;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\HotelBooking\BookingPeriod;
use Sdk\Booking\ValueObject\HotelBooking\HotelInfo;
use Sdk\Booking\ValueObject\ServiceId;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;
use Sdk\Shared\ValueObject\Time;

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
