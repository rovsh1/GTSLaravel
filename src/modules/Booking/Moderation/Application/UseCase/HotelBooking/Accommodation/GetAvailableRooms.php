<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase\HotelBooking\Accommodation;

use Carbon\CarbonPeriod;
use Module\Booking\Moderation\Application\Exception\HotelDetailsExpectedException;
use Module\Booking\Shared\Domain\Booking\Adapter\HotelRoomAdapterInterface;
use Module\Booking\Shared\Domain\Booking\Adapter\HotelQuotaAdapterInterface;
use Module\Booking\Shared\Domain\Booking\Entity\HotelBooking;
use Module\Booking\Shared\Domain\Booking\Repository\DetailsRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Hotel\Moderation\Application\Dto\RoomDto;
use Module\Shared\Enum\Booking\QuotaProcessingMethodEnum;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetAvailableRooms implements UseCaseInterface
{
    public function __construct(
        private readonly DetailsRepositoryInterface $detailsRepository,
        private readonly HotelRoomAdapterInterface $hotelRoomAdapter,
        private readonly HotelQuotaAdapterInterface $roomQuotaAdapter
    ) {
    }

    /**
     * @param int $bookingId
     * @return array<int, RoomDto>
     */
    public function execute(int $bookingId): array
    {
        $details = $this->detailsRepository->findOrFail(new BookingId($bookingId));
        if (!$details instanceof HotelBooking) {
            throw new HotelDetailsExpectedException();
        }
        $hotelId = $details->hotelInfo()->id();
        $rooms = $this->hotelRoomAdapter->getByHotelId($hotelId);
        if ($details->quotaProcessingMethod() !== QuotaProcessingMethodEnum::QUOTA) {
            return $rooms;
        }

        $period = CarbonPeriod::create(
            $details->bookingPeriod()->dateFrom(),
            $details->bookingPeriod()->dateTo(),
            'P1D'
        );

        return array_filter(
            $rooms,
            fn($room) => $this->roomQuotaAdapter->getAvailableCount($room->id, $period) > 0
        );
    }
}
