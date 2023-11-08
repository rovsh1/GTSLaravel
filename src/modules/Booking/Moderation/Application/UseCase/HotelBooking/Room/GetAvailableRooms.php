<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase\HotelBooking\Room;

use Carbon\CarbonPeriod;
use Module\Booking\Shared\Domain\Booking\Adapter\HotelRoomAdapterInterface;
use Module\Booking\Shared\Domain\Booking\Adapter\HotelRoomQuotaAdapterInterface;
use Module\Booking\Shared\Domain\Booking\Repository\Details\HotelBookingRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Hotel\Moderation\Application\Admin\Response\RoomDto;
use Module\Shared\Enum\Booking\QuotaProcessingMethodEnum;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetAvailableRooms implements UseCaseInterface
{
    public function __construct(
        private readonly HotelBookingRepositoryInterface $detailsRepository,
        private readonly HotelRoomAdapterInterface $hotelRoomAdapter,
        private readonly HotelRoomQuotaAdapterInterface $roomQuotaAdapter
    ) {}

    /**
     * @param CarbonPeriod $period
     * @return array<int, RoomDto>
     */
    public function execute(int $bookingId): array
    {
        $details = $this->detailsRepository->findOrFail(new BookingId($bookingId));
        $hotelId = $details->hotelInfo()->id();
        $rooms = $this->hotelRoomAdapter->getByHotelId($hotelId);
        if ($details->quotaProcessingMethod() !== QuotaProcessingMethodEnum::QUOTA) {
            return $rooms;
        }
        $availableRooms = [];
        $period = CarbonPeriod::create($details->bookingPeriod()->dateFrom(), $details->bookingPeriod()->dateTo(), 'P1D');
        $countDays = $period->excludeEndDate()->count();
        foreach ($rooms as $room) {
            $quotas = $this->roomQuotaAdapter->getAvailable($hotelId, $period, $room->id);
            $countAvailableQuotas = count($quotas);
            if ($countDays === $countAvailableQuotas) {
                $availableRooms[] = $room;
            }
        }

        return $availableRooms;
    }
}
