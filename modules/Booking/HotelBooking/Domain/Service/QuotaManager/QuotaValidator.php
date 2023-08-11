<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Domain\Service\QuotaManager;

use Module\Booking\HotelBooking\Domain\Entity\Booking;
use Module\Booking\HotelBooking\Domain\Entity\RoomQuota;
use Module\Booking\HotelBooking\Domain\Repository\BookingQuotaRepositoryInterface;
use Module\Booking\HotelBooking\Domain\Service\QuotaManager\Model\QuotaCountByDate;
use Module\Booking\HotelBooking\Domain\Service\QuotaManager\Model\QuotaInterface;
use Module\Hotel\Infrastructure\Models\Room\QuotaStatusEnum;

class QuotaValidator
{
    /** @var array<int, RoomQuota> $quotas */
    public array $quotas = [];

    public function __construct(
        private readonly BookingQuotaRepositoryInterface $quotaRepository
    ) {
    }

    /**
     * @param Booking $booking
     * @return array<int, QuotaCountByDate>
     * @throws \Exception
     */
    public function getQuotasIfAvailable(Booking $booking): array
    {
        $quotas = $this->quotaRepository->getAvailableQuotas($booking);
        foreach ($quotas as $quota) {
            $hash = $this->getQuotaHash($quota);
            $this->quotas[$hash] = $quota;
        }

        $countRoomsByDate = $this->countRooms($booking);
        foreach ($countRoomsByDate as $roomCount) {
            $hash = $this->getQuotaHash($roomCount);
            $availableQuota = $this->quotas[$hash] ?? null;
            if ($availableQuota === null) {
                throw new \Exception('Нет квот на дату');
            }
            $isOpened = $availableQuota->status() === QuotaStatusEnum::OPEN;
            $isAvailableCount = $availableQuota->countAvailable() <= $roomCount->count;
            if (!$isOpened || !$isAvailableCount) {
                throw new \Exception('Недостаточно квот на дату');
            }
        }

        return $countRoomsByDate;
    }

    /**
     * @param Booking $booking
     * @return array<int, QuotaCountByDate>
     */
    private function countRooms(Booking $booking): array
    {
        $roomsCount = [];
        foreach ($booking->roomBookings() as $roomBooking) {
            $hotelRoomId = $roomBooking->roomInfo()->id();
            if (!array_key_exists($hotelRoomId, $roomsCount)) {
                $roomsCount[$hotelRoomId] = 1;
            } else {
                $roomsCount[$hotelRoomId]++;
            }
        }

        $roomsCountByDate = [];
        foreach ($booking->period()->includedDates() as $date) {
            foreach ($roomsCount as $roomId => $count) {
                $roomsCountByDate[] = new QuotaCountByDate(
                    roomId: $roomId,
                    date: $date,
                    count: $count
                );
            }
        }

        return $roomsCountByDate;
    }

    private function getQuotaHash(QuotaInterface $roomQuota): string
    {
        return "{$roomQuota->roomId()}_{$roomQuota->date()->toDateString()}";
    }
}
