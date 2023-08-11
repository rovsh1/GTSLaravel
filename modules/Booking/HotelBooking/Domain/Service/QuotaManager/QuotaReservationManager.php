<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Domain\Service\QuotaManager;

use Carbon\CarbonImmutable;
use Module\Booking\Common\Domain\ValueObject\BookingId;
use Module\Booking\HotelBooking\Domain\Entity\Booking;
use Module\Booking\HotelBooking\Domain\Repository\BookingQuotaRepositoryInterface;
use Module\Shared\Domain\Service\Context;

class QuotaReservationManager
{
    public function __construct(
        private readonly BookingQuotaRepositoryInterface $quotaRepository,
        private readonly Context $context,
        private readonly QuotaValidator $quotaValidator
    ) {
    }

    /**
     * @param Booking $booking
     * @return void
     * @throws \Throwable
     */
    public function reserve(Booking $booking): void
    {
        $this->executeWithAvailableQuota(
            $booking,
            function (BookingId $id, int $roomId, CarbonImmutable $date, int $count, array $context) {
                $this->quotaRepository->reserve($id, $roomId, $date, $count, $context);
            }
        );
    }

    /**
     * @param Booking $booking
     * @return void
     * @throws \Throwable
     */
    public function book(Booking $booking): void
    {
        $this->executeWithAvailableQuota(
            $booking,
            function (BookingId $id, int $roomId, CarbonImmutable $date, int $count, array $context) {
                $this->quotaRepository->book($id, $roomId, $date, $count, $context);
            }
        );
    }

    public function cancel(Booking $booking): void
    {
        $this->quotaRepository->startTransaction();
        $this->quotaRepository->resetByBookingId($booking->id());
        $this->quotaRepository->commitTransaction();
    }

    private function executeWithAvailableQuota(Booking $booking, callable $callback): void
    {
        $availableQuotas = $this->quotaValidator->getQuotasIfAvailable($booking);
        $this->quotaRepository->startTransaction();
        $this->quotaRepository->resetByBookingId($booking->id());

        foreach ($availableQuotas as $quota) {
            //@todo тут по хорошему иметь id квоты, чтобы не делать 100500 запросов в базу
            $callback(
                $booking->id(),
                $quota->roomId,
                $quota->date->toImmutable(),
                $quota->count,
                $this->context->get()
            );
        }

        $this->quotaRepository->commitTransaction();
    }
}
