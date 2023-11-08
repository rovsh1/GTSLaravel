<?php

namespace Module\Booking\Pricing\Domain\Booking\Service\HotelPriceCalculator;

use Illuminate\Support\Facades\DB;
use Module\Booking\Pricing\Domain\Booking\Adapter\HotelPricingAdapterInterface;
use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Repository\Details\HotelBookingRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingPriceItem;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingPrices;
use Module\Hotel\Pricing\Application\Dto\CalculatedHotelRoomsPricesDto;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;

class PriceCalculator
{
    public function __construct(
        private readonly HotelPricingAdapterInterface $hotelPricingAdapter,
        private readonly BookingRepositoryInterface $bookingRepository,
        private readonly HotelBookingRepositoryInterface $bookingDetailsRepository,
        private readonly RoomBookingPriceStorage $roomPricesStorage,
        private readonly CalculateHotelPriceRequestDtoBuilder $calculateHotelPriceRequestDtoBuilder,
        private readonly DomainEventDispatcherInterface $domainEventDispatcher,
    ) {}

    public function calculate(Booking $booking): void
    {
        $details = $this->bookingDetailsRepository->findOrFail($booking->id());
        $supplierPriceDto = $this->hotelPricingAdapter->calculate(
            $this->calculateHotelPriceRequestDtoBuilder
                ->booking($booking, $details)
                ->build()
        );

        $clientPriceDto = $this->hotelPricingAdapter->calculate(
            $this->calculateHotelPriceRequestDtoBuilder
                ->booking($booking, $details)
                ->withClientMarkups()
                ->build()
        );

        $booking->updatePrice($this->buildBookingPrice($booking, $supplierPriceDto, $clientPriceDto));

        DB::transaction(function () use ($booking, $details, $supplierPriceDto, $clientPriceDto) {
            $this->roomPricesStorage->store($details, $supplierPriceDto, $clientPriceDto);

            $this->bookingRepository->store($booking);
        });

        $this->domainEventDispatcher->dispatch(...$booking->pullEvents());
    }

    public function calculateByBookingId(BookingId $bookingId): void
    {
        $booking = $this->bookingRepository->findOrFail($bookingId);
        $this->calculate($booking);
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
