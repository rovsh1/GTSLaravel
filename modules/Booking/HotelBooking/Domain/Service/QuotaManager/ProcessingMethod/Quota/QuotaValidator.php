<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Domain\Service\QuotaManager\ProcessingMethod\Quota;

use Module\Booking\HotelBooking\Domain\Entity\Booking;
use Module\Booking\HotelBooking\Domain\Entity\RoomQuota;
use Module\Booking\HotelBooking\Domain\Repository\BookingQuotaRepositoryInterface;
use Module\Booking\HotelBooking\Domain\Service\QuotaManager\Exception\ClosedRoomDateQuota;
use Module\Booking\HotelBooking\Domain\Service\QuotaManager\Exception\NotEnoughRoomDateQuota;
use Module\Booking\HotelBooking\Domain\Service\QuotaManager\Exception\NotFoundRoomDateQuota;
use Module\Booking\HotelBooking\Domain\Service\QuotaManager\Model\BookingQuotaReservation;
use Module\Booking\HotelBooking\Domain\Service\QuotaManager\Model\QuotaInterface;
use Module\Booking\HotelBooking\Domain\Service\QuotaManager\Model\RoomDateQuotaReservation;
use Module\Hotel\Infrastructure\Models\Room\QuotaStatusEnum;

class QuotaValidator
{
    /** @var array<string, RoomQuota> $quotas */
    private array $quotas = [];

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
        $bookingQuotaReservation = new BookingQuotaReservation($booking);
        $roomQuotaReservations = $bookingQuotaReservation->getRoomQuotaReservations();
        $this->loadAvailableQuotas($booking, $bookingQuotaReservation->getRoomIds());
        foreach ($roomQuotaReservations as $roomQuotaReservation) {
            $this->ensureDateQuotaAvailable($roomQuotaReservation);
            /** @var RoomQuota $availableQuota */
            $availableQuota = $this->getAvailableRoomQuota($roomQuotaReservation);
            $roomQuotaReservation->setQuotaId($availableQuota->id());
        }

        return $roomQuotaReservations;
    }

    /**
     * @param Booking $booking
     * @param int $roomId
     * @return void
     * @throws ClosedRoomDateQuota
     * @throws NotEnoughRoomDateQuota
     * @throws NotFoundRoomDateQuota
     */
    public function ensureRoomAvailable(Booking $booking, int $roomId): void
    {
        $bookingQuotaReservation = new BookingQuotaReservation($booking);
        $bookingQuotaReservation->addRoom($roomId);
        $this->loadAvailableQuotas($booking, $bookingQuotaReservation->getRoomIds());
        $roomQuotaReservations = $bookingQuotaReservation->getRoomQuotaReservations();
        foreach ($roomQuotaReservations as $roomQuotaReservation) {
            $this->ensureDateQuotaAvailable($roomQuotaReservation);
        }
    }

    private function loadAvailableQuotas(Booking $booking, array $roomIds): void
    {
        $quotas = $this->quotaRepository->getAvailableQuotas($booking->period(), $roomIds);
        foreach ($quotas as $quota) {
            $hash = $this->getQuotaHash($quota);
            $this->quotas[$hash] = $quota;
        }
    }

    private function ensureDateQuotaAvailable(RoomDateQuotaReservation $roomQuotaReservation): void
    {
        $availableQuota = $this->getAvailableRoomQuota($roomQuotaReservation);
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
    }

    private function getAvailableRoomQuota(RoomDateQuotaReservation $roomQuotaReservation): ?RoomQuota
    {
        $hash = $this->getQuotaHash($roomQuotaReservation);

        return $this->quotas[$hash] ?? null;
    }

    private function getQuotaHash(QuotaInterface $roomQuota): string
    {
        return "{$roomQuota->roomId()}_{$roomQuota->date()->toDateString()}";
    }
}
