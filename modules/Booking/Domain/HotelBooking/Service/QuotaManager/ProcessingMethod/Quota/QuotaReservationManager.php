<?php

declare(strict_types=1);

namespace Module\Booking\Domain\HotelBooking\Service\QuotaManager\ProcessingMethod\Quota;

use Module\Booking\Domain\HotelBooking\HotelBooking;
use Module\Booking\Domain\HotelBooking\Repository\BookingQuotaRepositoryInterface;
use Module\Booking\Domain\HotelBooking\Service\QuotaManager\Exception\ClosedRoomDateQuota;
use Module\Booking\Domain\HotelBooking\Service\QuotaManager\Exception\NotEnoughRoomDateQuota;
use Module\Booking\Domain\HotelBooking\Service\QuotaManager\Exception\NotFoundRoomDateQuota;
use Module\Booking\Domain\HotelBooking\ValueObject\QuotaId;
use Module\Booking\Domain\Shared\ValueObject\BookingId;

class QuotaReservationManager
{
    public function __construct(
        private readonly BookingQuotaRepositoryInterface $quotaRepository,
        private readonly QuotaValidator $quotaValidator
    ) {}

    /**
     * @param HotelBooking $booking
     * @return void
     * @throws NotFoundRoomDateQuota
     * @throws ClosedRoomDateQuota
     * @throws NotEnoughRoomDateQuota
     */
    public function reserve(HotelBooking $booking): void
    {
        $this->executeWithAvailableQuota(
            $booking,
            function (BookingId $id, QuotaId $quotaId, int $count, array $context) {
                $this->quotaRepository->reserve($id, $quotaId, $count, $context);
            }
        );
    }

    /**
     * @param HotelBooking $booking
     * @return void
     * @throws NotFoundRoomDateQuota
     * @throws ClosedRoomDateQuota
     * @throws NotEnoughRoomDateQuota
     */
    public function book(HotelBooking $booking): void
    {
        $this->executeWithAvailableQuota(
            $booking,
            function (BookingId $id, QuotaId $quotaId, int $count, array $context) {
                $this->quotaRepository->book($id, $quotaId, $count, $context);
            }
        );
    }

    public function cancel(HotelBooking $booking): void
    {
        $this->quotaRepository->cancel($booking->id());
    }

    /**
     * @param HotelBooking $booking
     * @param callable $callback
     * @return void
     * @throws NotFoundRoomDateQuota
     * @throws ClosedRoomDateQuota
     * @throws NotEnoughRoomDateQuota
     */
    private function executeWithAvailableQuota(HotelBooking $booking, callable $callback): void
    {
        //сбрасываю квоты до получения доступных, т.к. если получать до сброса комнаты из брони минусуют available.
        $this->quotaRepository->cancel($booking->id());
        $availableQuotas = $this->quotaValidator->getQuotasIfAvailable($booking);

        foreach ($availableQuotas as $quota) {
            $callback(
                $booking->id(),
                $quota->quotaId(),
                $quota->count,
                []
            );
        }
    }
}
