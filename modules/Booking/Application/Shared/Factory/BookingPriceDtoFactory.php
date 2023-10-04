<?php

declare(strict_types=1);

namespace Module\Booking\Application\Shared\Factory;

use Module\Booking\Application\Shared\Response\BookingPriceDto;
use Module\Booking\Application\Shared\Response\PriceItemDto;
use Module\Booking\Domain\Shared\ValueObject\BookingPrice;
use Module\Booking\Domain\Shared\ValueObject\PriceItem;
use Module\Shared\Application\Dto\CurrencyDto;
use Module\Shared\Domain\Adapter\CurrencyRateAdapterInterface;
use Module\Shared\Domain\Service\TranslatorInterface;

class BookingPriceDtoFactory
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly CurrencyRateAdapterInterface $currencyRateAdapter,
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

        $netPriceValue = $this->getPriceValue($entity->netPrice());
        $grossPriceValue = $this->getPriceValue($entity->grossPrice());
        $calculatedProfitValue = $grossPriceValue - $netPriceValue;
        if ($entity->netPrice()->currency() !== $entity->grossPrice()->currency()) {
            $netConvertedValue = $this->currencyRateAdapter->convertNetRate(
                $netPriceValue,
                $entity->netPrice()->currency(),
                $entity->grossPrice()->currency(),
                'UZ'
            );
            $calculatedProfitValue = $grossPriceValue - $netConvertedValue;
        }
        $profit = new PriceItemDto(
            CurrencyDto::fromEnum($entity->grossPrice()->currency(), $this->translator),
            $calculatedProfitValue,
            null,
            null,
            false
        );

        return new BookingPriceDto($netPrice, $grossPrice, $profit);
    }

    private function getPriceValue(PriceItem $price): float
    {
        return $price->manualValue() !== null ? $price->manualValue() : $price->calculatedValue();
    }
}
