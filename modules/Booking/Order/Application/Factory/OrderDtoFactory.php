<?php

declare(strict_types=1);

namespace Module\Booking\Order\Application\Factory;

use Module\Booking\Order\Application\Dto\OrderDto;
use Module\Booking\Order\Domain\Entity\Order;
use Module\Shared\Application\Dto\CurrencyDto;
use Module\Shared\Domain\Service\TranslatorInterface;

class OrderDtoFactory
{
    public function __construct(
        private readonly TranslatorInterface $translator
    ) {}

    public function createFromEntity(Order $entity): OrderDto
    {
        return new OrderDto(
            $entity->id()->value(),
            CurrencyDto::fromEnum($entity->currency(), $this->translator),
            $entity->clientId()->value(),
            $entity->legalId()?->value(),
            $entity->createdAt()
        );
    }

    public function createCollection(array $orders): array
    {
        return array_map(fn(Order $order) => $this->createFromEntity($order), $orders);
    }
}
