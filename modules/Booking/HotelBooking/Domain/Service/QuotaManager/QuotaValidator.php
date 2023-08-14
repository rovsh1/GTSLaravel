<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Domain\Service\QuotaManager;

use Module\Booking\HotelBooking\Domain\Entity\Booking;
use Module\Booking\HotelBooking\Domain\Entity\RoomQuota;
use Module\Booking\HotelBooking\Domain\Repository\BookingQuotaRepositoryInterface;
use Module\Booking\HotelBooking\Domain\Service\QuotaManager\Exception\ClosedRoomDateQuota;
use Module\Booking\HotelBooking\Domain\Service\QuotaManager\Exception\NotEnoughRoomDateQuota;
use Module\Booking\HotelBooking\Domain\Service\QuotaManager\Exception\NotFoundRoomDateQuota;
use Module\Booking\HotelBooking\Domain\Service\QuotaManager\Model\RoomDateQuotaReservation;
use Module\Booking\HotelBooking\Domain\Service\QuotaManager\Model\QuotaInterface;
use Module\Hotel\Infrastructure\Models\Room\QuotaStatusEnum;

class QuotaValidator
{
    /** @var array<int, RoomQuota> $quotas */
    public array $quotas = [];

    public function __construct(
        private readonly BookingQuotaRepositoryInterface $quotaRepository
    ) {}

    /**
     * @param Booking $booking
     * @return array<int, RoomDateQuotaReservation>
     * @throws NotFoundRoomDateQuota
     * @throws ClosedRoomDateQuota
     * @throws NotEnoughRoomDateQuota
     */
    public function getQuotasIfAvailable(Booking $booking): array
    {
        $quotas = $this->quotaRepository->getAvailableQuotas($booking);
        foreach ($quotas as $quota) {
            $hash = $this->getQuotaHash($quota);
            $this->quotas[$hash] = $quota;
        }

        $roomQuotaReservations = $this->getRoomQuotaReservations($booking);
        foreach ($roomQuotaReservations as $roomQuotaReservation) {
            $hash = $this->getQuotaHash($roomQuotaReservation);
            $availableQuota = $this->quotas[$hash] ?? null;
            if ($availableQuota === null) {
                throw new NotFoundRoomDateQuota('Нет квот на дату');
            }
            $isOpened = $availableQuota->status() === QuotaStatusEnum::OPEN;
            if (!$isOpened) {
                throw new ClosedRoomDateQuota('Квота на дату закрыта');
            }
            $isAvailableCount = $roomQuotaReservation->count <= $availableQuota->countAvailable();
            if (!$isAvailableCount) {
                throw new NotEnoughRoomDateQuota('Недостаточно квот на дату');
            }
            //@todo уточнить у Сергея, т.к. сейчас костыльно
            $roomQuotaReservation->setQuotaId($availableQuota->id());
        }

        return $roomQuotaReservations;
    }

    /**
     * @param Booking $booking
     * @return array<int, RoomDateQuotaReservation>
     */
    private function getRoomQuotaReservations(Booking $booking): array
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
                $roomsCountByDate[] = new RoomDateQuotaReservation(
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
