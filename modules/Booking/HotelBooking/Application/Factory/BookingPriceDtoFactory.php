<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Application\Factory;

use Module\Booking\Common\Domain\ValueObject\BookingPrice;
use Module\Booking\HotelBooking\Application\Dto\BookingPriceDto;
use Module\Booking\HotelBooking\Application\Dto\PriceItemDto;
use Module\Shared\Application\Dto\CurrencyDto;
use Module\Shared\Domain\Service\TranslatorInterface;

class BookingPriceDtoFactory
{
    public function __construct(
        private readonly TranslatorInterface $translator
    ) {}

    public function createFromEntity(BookingPrice $entity): BookingPriceDto
    {
        $grossPrice = new PriceItemDto(
            CurrencyDto::fromEnum($entity->grossPrice()->currency(), $this->translator),
            $entity->grossPrice()->calculatedValue(),
            $entity->grossPrice()->manualValue(),
            $entity->grossPrice()->penaltyValue(),
            $entity->grossPrice()->manualValue() !== null,
        );

        $netPrice = new PriceItemDto(
            CurrencyDto::fromEnum($entity->netPrice()->currency(), $this->translator),
            $entity->netPrice()->calculatedValue(),
            $entity->netPrice()->manualValue(),
            $entity->netPrice()->penaltyValue(),
            $entity->netPrice()->manualValue() !== null,
        );

        return new BookingPriceDto($netPrice, $grossPrice);
    }

}
