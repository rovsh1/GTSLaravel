<?php

declare(strict_types=1);

namespace Module\Booking\Common\Domain\Service;

use Module\Booking\Common\Domain\ValueObject\BookingPriceNew;
use Module\Booking\Common\Domain\ValueObject\PriceItem;

class BookingPriceChangeDecorator
{
    private BookingPriceNew $priceAfter;

    public function __construct(private readonly BookingPriceNew $price) {}

    public function setNetPrice(PriceItem $price): static
    {
        $this->priceAfter = new BookingPriceNew(
            netPrice: $price,
            grossPrice: $this->price->grossPrice(),
        );

        return $this;
    }

    public function setGrossPrice(PriceItem $price): static
    {
        $this->priceAfter = new BookingPriceNew(
            netPrice: $this->price->netPrice(),
            grossPrice: $price,
        );

        return $this;
    }

    public function setPrices(PriceItem $netPrice, PriceItem $grossPrice): static
    {
        $this->priceAfter = new BookingPriceNew(
            grossPrice: $grossPrice,
            netPrice: $netPrice,
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
        $this->priceAfter = new BookingPriceNew(
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
        $this->priceAfter = new BookingPriceNew(
            netPrice: $this->price->netPrice(),
            grossPrice: $grossPrice,
        );

        return $this;
    }

    public function getPriceAfter(): BookingPriceNew
    {
        return $this->priceAfter;
    }

    public function getPriceBefore(): BookingPriceNew
    {
        return $this->price;
    }

    public function isPriceChanged(): bool
    {
        return !$this->price->isEqual($this->priceAfter);
    }
}
