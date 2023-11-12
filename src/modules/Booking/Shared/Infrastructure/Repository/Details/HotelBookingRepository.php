<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Repository\Details;

use Module\Booking\Shared\Domain\Booking\Entity\HotelBooking;
use Module\Booking\Shared\Domain\Booking\Repository\Details\HotelBookingRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\BookingPeriod;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\HotelInfo;
use Module\Booking\Shared\Infrastructure\Factory\Details\HotelBookingFactory;
use Module\Booking\Shared\Infrastructure\Models\Booking;
use Module\Booking\Shared\Infrastructure\Models\Details\Hotel;
use Module\Shared\Enum\Booking\QuotaProcessingMethodEnum;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class HotelBookingRepository implements HotelBookingRepositoryInterface
{
    public function find(BookingId $bookingId): HotelBooking
    {
        $booking = Booking::find($bookingId->value());
        if ($booking === null) {
            throw new EntityNotFoundException('Booking not found');
        }

        return $this->build($booking->hotelDetails);
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
                'hotelInfo' => $hotelInfo->toData(),
                'period' => $bookingPeriod->toData(),
            ]
        ]);

        return $this->build(Hotel::find($model->id));
    }

    public function store(HotelBooking $details): bool
    {
        return (bool)Hotel::whereId($details->id()->value())->update([
            'date_start' => $details->bookingPeriod()->dateFrom(),
            'date_end' => $details->bookingPeriod()->dateTo(),
            'nights_count' => $details->bookingPeriod()->nightsCount(),
            'data' => [
                'hotelInfo' => $details->hotelInfo()->toData(),
                'period' => $details->bookingPeriod()->toData(),
                'externalNumber' => $details->externalNumber()?->toData(),
            ]
        ]);
    }

    private function build(Hotel $data): HotelBooking
    {
        return (new HotelBookingFactory)->build($data);
    }
}
