<?php

namespace Module\Booking\EventSourcing\Domain\Service\EventDescriptor;

use Module\Booking\EventSourcing\Domain\ValueObject\EventGroupEnum;
use Sdk\Booking\Dto\PriceDto;
use Sdk\Booking\IntegrationEvent\ClientPriceChanged;
use Sdk\Booking\IntegrationEvent\SupplierPriceChanged;
use Sdk\Module\Contracts\Event\IntegrationEventInterface;

class PriceDescriptor extends AbstractDescriptor implements DescriptorInterface
{
    public function build(IntegrationEventInterface $event): DescriptionDto
    {
        assert($event instanceof SupplierPriceChanged || $event instanceof ClientPriceChanged);

        return new DescriptionDto(
            group: EventGroupEnum::PRICE_CHANGED,
            field: 'price',
            description: $this->buildDescription($event),
            before: $event->before->toArray(),
            after: $event->after->toArray()
        );
    }

    private function buildDescription(SupplierPriceChanged|ClientPriceChanged $event): string
    {
        return "Стоимость для {$this->priceType($event::class)} бронирования изменена: "
            . $this->compare($event->before, $event->after);
    }

    private function priceType($event): string
    {
        return match ($event) {
            SupplierPriceChanged::class => 'поставщика',
            ClientPriceChanged::class => 'клиента',
        };
    }

    private function compare(PriceDto $a, PriceDto $b): string
    {
        static $assoc = [
            'currency' => 'Валюта',
            'calculatedValue' => 'Расчетная',
            'manualValue' => 'Установленная',
            'penaltyValue' => 'Штраф',
        ];

        $changes = [];
        foreach ($assoc as $key => $label) {
            if ($a->$key === $b->$key) {
                continue;
            }
            $changes[] = "{$this->valuePresentation($a->$key)} &rarr; {$this->valuePresentation($b->$key)} ($label)";
        }

        return implode(', ', $changes);
    }
}