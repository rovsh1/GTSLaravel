<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Shared\Service;

use Module\Booking\Domain\Shared\ValueObject\BookingPrice;
use Module\Booking\Domain\Shared\ValueObject\PriceItem;

class BookingPriceChangeDecorator
{
    private BookingPrice $priceAfter;

    public function __construct(private readonly BookingPrice $price) {}

    public function setNetPrice(PriceItem $price): static
    {
        $this->priceAfter = new BookingPrice(
            netPrice: $price,
            grossPrice: $this->price->grossPrice(),
        );

        return $this;
    }

    public function setGrossPrice(PriceItem $price): static
    {
        $this->priceAfter = new BookingPrice(
            netPrice: $this->price->netPrice(),
            grossPrice: $price,
        );

        return $this;
    }

    public function setPrices(PriceItem $netPrice, PriceItem $grossPrice): static
    {
        $this->priceAfter = new BookingPrice(
            netPrice: $netPrice,
            grossPrice: $grossPrice,
        );

        return $this;
    }

    public function setNetPenalty(float|null $amount): static
    {
        $netPrice = new PriceItem(
            currency: $this->price->netPrice()->currency(),
            calculatedValue: $this->price->netPrice()->calculatedValue(),
            manualValue: $this->price->netPrice()->manualValue(),
            penaltyValue: $amount
        );
        $this->priceAfter = new BookingPrice(
            netPrice: $netPrice,
            grossPrice: $this->price->grossPrice()
        );

        return $this;
    }

    public function setGrossPenalty(float|null $amount): static
    {
        $grossPrice = new PriceItem(
            currency: $this->price->grossPrice()->currency(),
            calculatedValue: $this->price->grossPrice()->calculatedValue(),
            manualValue: $this->price->grossPrice()->manualValue(),
            penaltyValue: $amount
        );
        $this->priceAfter = new BookingPrice(
            netPrice: $this->price->netPrice(),
            grossPrice: $grossPrice,
        );

        return $this;
    }

    public function getPriceAfter(): BookingPrice
    {
        return $this->priceAfter;
    }

    public function getPriceBefore(): BookingPrice
    {
        return $this->price;
    }

    public function isPriceChanged(): bool
    {
        return !$this->price->isEqual($this->priceAfter);
    }
}
