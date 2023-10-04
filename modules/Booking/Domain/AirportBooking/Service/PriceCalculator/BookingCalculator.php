<?php

declare(strict_types=1);

namespace Module\Booking\Domain\AirportBooking\Service\PriceCalculator;

use Module\Booking\Domain\AirportBooking\AirportBooking;
use Module\Booking\Domain\AirportBooking\Service\PriceCalculator\Support\ServicePriceFetcher;
use Module\Booking\Domain\Order\Repository\OrderRepositoryInterface;
use Module\Booking\Domain\Shared\Entity\BookingInterface;
use Module\Booking\Domain\Shared\Service\BookingCalculatorInterface;
use Module\Booking\Domain\Shared\ValueObject\PriceItem;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class BookingCalculator implements BookingCalculatorInterface
{
    public function __construct(
        private readonly ServicePriceFetcher $priceFetcher,
        private readonly OrderRepositoryInterface $orderRepository,
    ) {}

    public function calculateGrossPrice(BookingInterface|AirportBooking $booking): PriceItem
    {
        return new PriceItem(
            currency: $booking->price()->grossPrice()->currency(),
            calculatedValue: $this->calculate($booking, 'grossPrice'),
            manualValue: null,
            penaltyValue: null,
        );
    }

    public function calculateNetPrice(BookingInterface|AirportBooking $booking): PriceItem
    {
        return new PriceItem(
            currency: $booking->price()->netPrice()->currency(),
            calculatedValue: $this->calculate($booking, 'netPrice'),
            manualValue: null,
            penaltyValue: null,
        );
    }

    private function calculate(AirportBooking $booking, string $priceType): float
    {
        $order = $this->orderRepository->find($booking->orderId()->value());
        if ($order === null) {
            throw new EntityNotFoundException('Order not found');
        }

        $price = $this->priceFetcher->fetch(
            $booking->serviceInfo()->supplierId(),
            $booking->serviceInfo()->id(),
            $booking->airportInfo()->id(),
            $booking->date(),
            $order->currency(),
        );

        return $price->$priceType * $booking->guestIds()->count();
    }
}
