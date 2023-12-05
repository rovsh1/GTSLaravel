<?php

namespace Module\Booking\Pricing\Domain\Booking\Service;

use Module\Booking\Pricing\Domain\Booking\Adapter\HotelPricingAdapterInterface;
use Module\Booking\Pricing\Domain\Booking\Service\HotelPriceCalculator\HotelPriceMapper;
use Module\Booking\Pricing\Domain\Booking\Service\HotelPriceCalculator\CalculateHotelPriceRequestDtoBuilder;
use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Booking\Repository\AccommodationRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Repository\DetailsRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Service\BookingUnitOfWorkInterface;
use Sdk\Booking\Entity\Details\HotelBooking;
use Sdk\Booking\ValueObject\BookingPrices;

class HotelPriceCalculator implements PriceCalculatorInterface
{
    public function __construct(
        private readonly DetailsRepositoryInterface $detailsRepository,
        private readonly HotelPricingAdapterInterface $hotelPricingAdapter,
        private readonly HotelPriceMapper $hotelPriceMapper,
        private readonly BookingUnitOfWorkInterface $bookingUnitOfWork,
        private readonly AccommodationRepositoryInterface $accommodationRepository,
        private readonly CalculateHotelPriceRequestDtoBuilder $calculateHotelPriceRequestDtoBuilder,
    ) {}

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

        $this->hotelPriceMapper->boot($booking, $supplierPriceDto, $clientPriceDto);
        foreach ($this->accommodationRepository->getByBookingId($details->bookingId()) as $accommodation) {
            $this->bookingUnitOfWork->persist($accommodation);
            $accommodation->updatePrices(
                $this->hotelPriceMapper->buildAccommodationPrice($accommodation)
            );
        }

        return $this->hotelPriceMapper->buildBookingPrice();
    }
}
