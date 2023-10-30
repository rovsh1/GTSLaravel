<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Booking\Service\HotelBooking\QuotaManager\ProcessingMethod\Quota;

use Module\Booking\Domain\Booking\Booking;
use Module\Booking\Domain\Booking\Entity\HotelBooking;
use Module\Booking\Domain\Booking\Repository\HotelBooking\BookingQuotaRepositoryInterface;
use Module\Booking\Domain\Booking\Service\HotelBooking\QuotaManager\Exception\ClosedRoomDateQuota;
use Module\Booking\Domain\Booking\Service\HotelBooking\QuotaManager\Exception\NotEnoughRoomDateQuota;
use Module\Booking\Domain\Booking\Service\HotelBooking\QuotaManager\Exception\NotFoundRoomDateQuota;
use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Domain\Booking\ValueObject\HotelBooking\QuotaId;

class QuotaReservationManager
{
    public function __construct(
        private readonly BookingQuotaRepositoryInterface $quotaRepository,
        private readonly QuotaValidator $quotaValidator
    ) {
    }

    /**
     * @param HotelBooking $booking
     * @return void
     * @throws NotFoundRoomDateQuota
     * @throws ClosedRoomDateQuota
     * @throws NotEnoughRoomDateQuota
     */
    public function reserve(Booking $booking, HotelBooking $details): void
    {
        $this->executeWithAvailableQuota(
            $booking,
            $details,
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
    public function book(Booking $booking, HotelBooking $details): void
    {
        $this->executeWithAvailableQuota(
            $booking,
            $details,
            function (BookingId $id, QuotaId $quotaId, int $count, array $context) {
                $this->quotaRepository->book($id, $quotaId, $count, $context);
            }
        );
    }

    public function cancel(Booking $booking): void
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
    private function executeWithAvailableQuota(Booking $booking, HotelBooking $details, callable $callback): void
    {
        //сбрасываю квоты до получения доступных, т.к. если получать до сброса комнаты из брони минусуют available.
        $this->quotaRepository->cancel($booking->id());
        $availableQuotas = $this->quotaValidator->getQuotasIfAvailable($details);

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
