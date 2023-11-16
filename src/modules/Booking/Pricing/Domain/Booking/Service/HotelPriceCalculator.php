<?php

namespace Module\Booking\Pricing\Domain\Booking\Service;

use Module\Booking\Pricing\Domain\Booking\Adapter\HotelPricingAdapterInterface;
use Module\Booking\Pricing\Domain\Booking\Service\HotelPriceCalculator\AccommodationPriceStorage;
use Module\Booking\Pricing\Domain\Booking\Service\HotelPriceCalculator\CalculateHotelPriceRequestDtoBuilder;
use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Booking\Entity\HotelBooking;
use Module\Booking\Shared\Domain\Booking\Repository\DetailsRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingPriceItem;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingPrices;
use Module\Hotel\Pricing\Application\Dto\CalculatedHotelRoomsPricesDto;

class HotelPriceCalculator implements PriceCalculatorInterface
{
    public function __construct(
        private readonly DetailsRepositoryInterface $detailsRepository,
        private readonly HotelPricingAdapterInterface $hotelPricingAdapter,
        private readonly AccommodationPriceStorage $roomPricesStorage,
        private readonly CalculateHotelPriceRequestDtoBuilder $calculateHotelPriceRequestDtoBuilder,
    ) {
    }

    public function calculate(Booking $booking): BookingPrices
    {
        $details = $this->detailsRepository->findOrFail($booking->id());
        assert($details instanceof HotelBooking);

        $supplierPriceDto = $this->hotelPricingAdapter->calculate(
            $this->calculateHotelPriceRequestDtoBuilder
                ->booking($booking)
                ->build()
        );

        $clientPriceDto = $this->hotelPricingAdapter->calculate(
            $this->calculateHotelPriceRequestDtoBuilder
                ->withClientMarkups()
                ->build()
        );

//        DB::transaction(function () use ($booking, $details, $supplierPriceDto, $clientPriceDto) {
        $this->roomPricesStorage->store($details, $supplierPriceDto, $clientPriceDto);

//            $this->bookingRepository->store($booking);
//        });

        return $this->buildBookingPrice($booking, $supplierPriceDto, $clientPriceDto);
    }

    private function buildBookingPrice(
        Booking $booking,
        CalculatedHotelRoomsPricesDto $supplierPriceDto,
        CalculatedHotelRoomsPricesDto $clientPriceDto
    ): BookingPrices {
        $bookingPrices = $booking->prices();

        return new BookingPrices(
            new BookingPriceItem(
                currency: $supplierPriceDto->currency,
                calculatedValue: $supplierPriceDto->price,
                manualValue: $bookingPrices->supplierPrice()->manualValue(),
                penaltyValue: $bookingPrices->supplierPrice()->penaltyValue()
            ),
            new BookingPriceItem(
                currency: $clientPriceDto->currency,
                calculatedValue: $clientPriceDto->price,
                manualValue: $bookingPrices->clientPrice()->manualValue(),
                penaltyValue: $bookingPrices->clientPrice()->penaltyValue()
            ),
        );
    }
}
