<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\ServiceBooking\Service\DetailsEditor\Editor;

use Module\Booking\Application\Admin\ServiceBooking\Service\DetailsEditor\EditorInterface;
use Module\Booking\Domain\Booking\Adapter\HotelAdapterInterface;
use Module\Booking\Domain\Booking\Entity\HotelBooking as Entity;
use Module\Booking\Domain\Booking\Repository\Details\HotelBookingRepositoryInterface;
use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Domain\Booking\ValueObject\HotelBooking\BookingPeriod;
use Module\Booking\Domain\Booking\ValueObject\HotelBooking\HotelInfo;
use Module\Booking\Domain\Booking\ValueObject\HotelBooking\RoomBookingIdCollection;
use Module\Booking\Domain\Booking\ValueObject\ServiceId;
use Module\Shared\Enum\Booking\QuotaProcessingMethodEnum;
use Module\Shared\ValueObject\Time;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class HotelBooking extends AbstractEditor implements EditorInterface
{
    public function __construct(
        private readonly HotelBookingRepositoryInterface $detailsRepository,
        private readonly HotelAdapterInterface $hotelAdapter,
    ) {}

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

        return $this->detailsRepository->create(
            $bookingId,
            $hotelInfo,
            $bookingPeriod,
            new RoomBookingIdCollection([]),
            QuotaProcessingMethodEnum::from((int)$detailsData['quota_processing_method']),
        );
    }

    public function update(BookingId $bookingId, array $detailsData): void
    {
        $details = $this->detailsRepository->find($bookingId);
        foreach ($detailsData as $field => $value) {
            $this->setField($details, $field, $value);
        }
        $this->detailsRepository->store($details);
    }
}
