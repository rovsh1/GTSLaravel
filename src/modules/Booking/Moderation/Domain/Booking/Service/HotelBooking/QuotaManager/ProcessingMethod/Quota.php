<?php

namespace Module\Booking\Moderation\Domain\Booking\Service\HotelBooking\QuotaManager\ProcessingMethod;

use Carbon\CarbonPeriod;
use Module\Booking\Moderation\Domain\Booking\Service\HotelBooking\QuotaManager\QuotaProcessingMethodInterface;
use Module\Booking\Shared\Domain\Booking\Adapter\HotelQuotaAdapterInterface;
use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Booking\Entity\HotelBooking;
use Module\Booking\Shared\Domain\Booking\Repository\AccommodationRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\BookingPeriod;
use Module\Hotel\Quotation\Application\Dto\BookingRoomDto;
use Module\Hotel\Quotation\Application\RequestDto\BookRequestDto;
use Module\Hotel\Quotation\Application\RequestDto\ReserveRequestDto;
use Sdk\Shared\Enum\Booking\BookingStatusEnum;

class Quota implements QuotaProcessingMethodInterface
{
    public function __construct(
        private readonly HotelQuotaAdapterInterface $quotaAdapter,
        private readonly AccommodationRepositoryInterface $accommodationRepository,
    ) {
    }

    public function process(Booking $booking, HotelBooking $details): void
    {
        if ($this->isBookingConfirmed($booking)) {
            $this->quotaAdapter->book(
                new BookRequestDto(
                    bookingId: $booking->id()->value(),
                    bookingPeriod: $this->buildRequestPeriod($details->bookingPeriod()),
                    bookingRooms: $this->buildRequestRooms($details)
                )
            );
        } elseif ($this->isBookingCancelled($booking)) {
            $this->quotaAdapter->cancel($booking->id()->value());
        } else {
            $this->quotaAdapter->reserve(
                new ReserveRequestDto(
                    bookingId: $booking->id()->value(),
                    bookingPeriod: $this->buildRequestPeriod($details->bookingPeriod()),
                    bookingRooms: $this->buildRequestRooms($details)
                )
            );
        }
    }

    private function isBookingConfirmed(Booking $booking): bool
    {
        //@todo надо перенести в рулзы
        return $booking->status() === BookingStatusEnum::CONFIRMED;
    }

    private function isBookingCancelled(Booking $booking): bool
    {
        return $booking->isCancelled() || $booking->isDeleted();
    }

    private function buildRequestPeriod(BookingPeriod $period): CarbonPeriod
    {
        return new CarbonPeriod($period->dateFrom(), $period->dateTo(), 'P1D');
    }

    private function buildRequestRooms(HotelBooking $details): array
    {
        $roomsCount = [];
        foreach ($this->accommodationRepository->getByBookingId($details->bookingId()) as $accommodation) {
            $hotelRoomId = $accommodation->roomInfo()->id();
            if (array_key_exists($hotelRoomId, $roomsCount)) {
                $roomsCount[$hotelRoomId]++;
            } else {
                $roomsCount[$hotelRoomId] = 1;
            }
        }

        $requestRooms = [];
        foreach ($roomsCount as $hotelRoomId => $count) {
            $requestRooms[] = new BookingRoomDto($hotelRoomId, $count);
        }

        return $requestRooms;
    }
}
