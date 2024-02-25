<?php

namespace Module\Booking\Shared\Domain\Booking\Service\IntegrationEventMapper;

use Sdk\Booking\Dto\PriceDto;
use Sdk\Booking\Event\PriceUpdated;
use Sdk\Booking\IntegrationEvent\ClientPriceChanged;
use Sdk\Booking\IntegrationEvent\SupplierPriceChanged;
use Sdk\Booking\ValueObject\BookingPriceItem;
use Sdk\Module\Contracts\Event\DomainEventInterface;

class PriceChangedMapper implements MapperInterface
{
    public function map(DomainEventInterface $event): array
    {
        assert($event instanceof PriceUpdated);

        $events = [];
        $beforePrices = $event->priceBefore;
        $afterPrices = $event->booking->prices();

        if (!$beforePrices->clientPrice()->isEqual($afterPrices->clientPrice())) {
            $events[] = new ClientPriceChanged(
                $event->bookingId()->value(),
                $this->makePriceDto($beforePrices->clientPrice()),
                $this->makePriceDto($afterPrices->clientPrice()),
            );
        }

        if (!$beforePrices->supplierPrice()->isEqual($afterPrices->supplierPrice())) {
            $events[] = new SupplierPriceChanged(
                $event->bookingId()->value(),
                $this->makePriceDto($beforePrices->supplierPrice()),
                $this->makePriceDto($afterPrices->supplierPrice()),
            );
        }

        return $events;
    }

    private function makePriceDto(BookingPriceItem $priceItem): PriceDto
    {
        return new PriceDto(
            currency: $priceItem->currency(),
            calculatedValue: $priceItem->calculatedValue(),
            manualValue: $priceItem->manualValue(),
            penaltyValue: $priceItem->penaltyValue()
        );
    }
}
