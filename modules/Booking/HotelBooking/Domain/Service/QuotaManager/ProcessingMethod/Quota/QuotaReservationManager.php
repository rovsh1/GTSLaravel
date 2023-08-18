<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Domain\Service\QuotaManager\ProcessingMethod\Quota;

use Module\Booking\Common\Domain\ValueObject\BookingId;
use Module\Booking\HotelBooking\Domain\Entity\Booking;
use Module\Booking\HotelBooking\Domain\Repository\BookingQuotaRepositoryInterface;
use Module\Booking\HotelBooking\Domain\Service\QuotaManager\Exception\ClosedRoomDateQuota;
use Module\Booking\HotelBooking\Domain\Service\QuotaManager\Exception\NotEnoughRoomDateQuota;
use Module\Booking\HotelBooking\Domain\Service\QuotaManager\Exception\NotFoundRoomDateQuota;
use Module\Booking\HotelBooking\Domain\ValueObject\QuotaId;
use Module\Shared\Domain\Service\Context;

class QuotaReservationManager
{
    public function __construct(
        private readonly BookingQuotaRepositoryInterface $quotaRepository,
        private readonly Context $context,
        private readonly QuotaValidator $quotaValidator
    ) {}

    /**
     * @param Booking $booking
     * @return void
     * @throws NotFoundRoomDateQuota
     * @throws ClosedRoomDateQuota
     * @throws NotEnoughRoomDateQuota
     */
    public function reserve(Booking $booking): void
    {
        $this->executeWithAvailableQuota(
            $booking,
            function (BookingId $id, QuotaId $quotaId, int $count, array $context) {
                $this->quotaRepository->reserve($id, $quotaId, $count, $context);
            }
        );
    }

    /**
     * @param Booking $booking
     * @return void
     * @throws NotFoundRoomDateQuota
     * @throws ClosedRoomDateQuota
     * @throws NotEnoughRoomDateQuota
     */
    public function book(Booking $booking): void
    {
        $this->executeWithAvailableQuota(
            $booking,
            function (BookingId $id, QuotaId $quotaId, int $count, array $context) {
                $this->quotaRepository->book($id, $quotaId, $count, $context);
            }
        );
    }

    public function cancel(Booking $booking): void
    {
        $this->quotaRepository->startTransaction();
        $this->quotaRepository->resetByBookingId($booking->id());
        $this->quotaRepository->commitTransaction();
    }

    /**
     * @param Booking $booking
     * @param callable $callback
     * @return void
     * @throws NotFoundRoomDateQuota
     * @throws ClosedRoomDateQuota
     * @throws NotEnoughRoomDateQuota
     */
    private function executeWithAvailableQuota(Booking $booking, callable $callback): void
    {
        $this->quotaRepository->startTransaction();
        //сбрасываю квоты до получения доступных, т.к. если получать до сброса комнаты из брони минусуют available.
        $this->quotaRepository->resetByBookingId($booking->id());
        try {
            $availableQuotas = $this->quotaValidator->getQuotasIfAvailable($booking);
        } catch (\Throwable $e) {
            $this->quotaRepository->rollBackTransaction();
            throw $e;
        }

        foreach ($availableQuotas as $quota) {
            $callback(
                $booking->id(),
                $quota->quotaId(),
                $quota->count,
                $this->context->get()
            );
        }

        $this->quotaRepository->commitTransaction();
    }
}
