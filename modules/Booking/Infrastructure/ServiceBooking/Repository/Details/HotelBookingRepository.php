<?php

declare(strict_types=1);

namespace Module\Booking\Infrastructure\ServiceBooking\Repository\Details;

use Module\Booking\Domain\Booking\Entity\HotelBooking;
use Module\Booking\Domain\Booking\Repository\Details\HotelBookingRepositoryInterface;
use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Domain\Booking\ValueObject\BookingPeriod;
use Module\Booking\Domain\Booking\ValueObject\HotelBooking\HotelInfo;
use Module\Booking\Domain\Booking\ValueObject\HotelBooking\RoomBookingIdCollection;
use Module\Booking\Infrastructure\ServiceBooking\Models\Booking;
use Module\Booking\Infrastructure\ServiceBooking\Models\Details\Hotel;
use Module\Shared\Enum\Booking\QuotaProcessingMethodEnum;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class HotelBookingRepository extends AbstractDetailsRepository implements HotelBookingRepositoryInterface
{
    public function find(BookingId $bookingId): HotelBooking
    {
        $booking = Booking::find($bookingId->value());
        if ($booking === null) {
            throw new EntityNotFoundException('Booking not found');
        }

        return $this->detailsFactory->buildByBooking($booking);
    }

    public function findOrFail(BookingId $bookingId): HotelBooking
    {
        $details = $this->find($bookingId);
        if ($details === null) {
            throw new EntityNotFoundException('Hotel booking not found');
        }

        return $details;
    }

    public function create(
        BookingId $bookingId,
        HotelInfo $hotelInfo,
        BookingPeriod $bookingPeriod,
        RoomBookingIdCollection $roomBookings,
        QuotaProcessingMethodEnum $quotaProcessingMethod,
    ): HotelBooking {
        $model = Hotel::create([
            'booking_id' => $bookingId->value(),
            'hotel_id' => $hotelInfo->id(),
            'date_start' => $bookingPeriod->dateFrom(),
            'date_end' => $bookingPeriod->dateTo(),
            'nights_count' => $bookingPeriod->nightsCount(),
            'quota_processing_method' => $quotaProcessingMethod,
            'data' => [
                'room_booking_ids' => $roomBookings->toData(),
                'hotelInfo' => $hotelInfo->toData(),
                'period' => $bookingPeriod->toData(),
            ]
        ]);

        return $this->detailsFactory->build(Hotel::find($model->id));
    }

    public function store(HotelBooking $details): bool
    {
        return (bool)Hotel::whereId($details->id()->value())->update([
            'date_start' => $details->bookingPeriod()->dateFrom(),
            'date_end' => $details->bookingPeriod()->dateTo(),
            'nights_count' => $details->bookingPeriod()->nightsCount(),
            'data' => [
                'room_booking_ids' => $details->roomBookings()->toData(),
                'hotelInfo' => $details->hotelInfo()->toData(),
                'period' => $details->bookingPeriod()->toData(),
                'externalNumber' => $details->externalNumber()?->toData(),
            ]
        ]);
    }
}
