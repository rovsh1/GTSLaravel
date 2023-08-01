<?php

declare(strict_types=1);

namespace Module\Booking\Common\Domain\Service;

use Module\Booking\Common\Domain\ValueObject\BookingPrice;
use Module\Booking\HotelBooking\Domain\ValueObject\ManualChangablePrice;

class BookingPriceChangeDecorator
{
    private BookingPrice $priceAfter;

    public function __construct(private readonly BookingPrice $price) {}

    public function setHoPrice(ManualChangablePrice $price): static
    {
        $this->priceAfter = new BookingPrice(
            netValue: $this->price->netValue(),
            boPrice: $this->price->boPrice(),
            hoPrice: $price,
            hoPenalty: $this->price->hoPenalty(),
            boPenalty: $this->price->boPenalty(),
        );

        return $this;
    }

    public function setBoPrice(ManualChangablePrice $price): static
    {
        $this->priceAfter = new BookingPrice(
            netValue: $this->price->netValue(),
            boPrice: $price,
            hoPrice: $this->price->hoPrice(),
            hoPenalty: $this->price->hoPenalty(),
            boPenalty: $this->price->boPenalty(),
        );

        return $this;
    }

    public function setPrices(ManualChangablePrice $boPrice, ManualChangablePrice $hoPrice): static
    {
        $this->priceAfter = new BookingPrice(
            netValue: $this->price->netValue(),
            boPrice: $boPrice,
            hoPrice: $hoPrice,
            hoPenalty: $this->price->hoPenalty(),
            boPenalty: $this->price->boPenalty(),
        );

        return $this;
    }

    public function setHoPenalty(float|null $amount): static
    {
        $this->priceAfter = new BookingPrice(
            netValue: $this->price->netValue(),
            boPrice: $this->price->boPrice(),
            hoPrice: $this->price->hoPrice(),
            hoPenalty: $amount,
            boPenalty: $this->price->boPenalty(),
        );

        return $this;
    }

    public function setBoPenalty(float|null $amount): static
    {
        $this->priceAfter = new BookingPrice(
            netValue: $this->price->netValue(),
            boPrice: $this->price->boPrice(),
            hoPrice: $this->price->hoPrice(),
            hoPenalty: $this->price->hoPenalty(),
            boPenalty: $amount,
        );

        return $this;
    }

    public function getPriceAfter(): BookingPrice
    {
        return $this->priceAfter;
    }

    /**
     * @return BookingPrice
     */
    public function getPriceBefore(): BookingPrice
    {
        return $this->price;
    }

    public function isPriceChanged(): bool
    {
        return !$this->price->isEqual($this->priceAfter);
    }
}
