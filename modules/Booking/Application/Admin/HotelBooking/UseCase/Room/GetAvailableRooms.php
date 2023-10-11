<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\HotelBooking\UseCase\Room;

use Carbon\CarbonPeriod;
use Module\Booking\Deprecated\HotelBooking\Adapter\HotelRoomAdapterInterface;
use Module\Booking\Deprecated\HotelBooking\Adapter\HotelRoomQuotaAdapterInterface;
use Module\Booking\Deprecated\HotelBooking\Repository\BookingRepositoryInterface;
use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Module\Catalog\Application\Admin\Response\RoomDto;
use Module\Shared\Enum\Booking\QuotaProcessingMethodEnum;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetAvailableRooms implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $bookingRepository,
        private readonly HotelRoomAdapterInterface $hotelRoomAdapter,
        private readonly HotelRoomQuotaAdapterInterface $roomQuotaAdapter
    ) {}

    /**
     * @param CarbonPeriod $period
     * @return array<int, RoomDto>
     */
    public function execute(int $bookingId): array
    {
        $booking = $this->bookingRepository->findOrFail(new BookingId($bookingId));
        $hotelId = $booking->hotelInfo()->id();
        $rooms = $this->hotelRoomAdapter->getByHotelId($hotelId);
        if ($booking->quotaProcessingMethod() !== QuotaProcessingMethodEnum::QUOTA) {
            return $rooms;
        }
        $availableRooms = [];
        $period = CarbonPeriod::create($booking->period()->dateFrom(), $booking->period()->dateTo(), 'P1D');
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
