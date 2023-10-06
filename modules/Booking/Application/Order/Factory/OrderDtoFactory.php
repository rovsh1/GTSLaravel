<?php

declare(strict_types=1);

namespace Module\Booking\Application\Order\Factory;

use Module\Booking\Application\Order\Response\OrderDto;
use Module\Booking\Domain\Order\Order;
use Module\Booking\Domain\Shared\ValueObject\GuestId;
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
            $entity->createdAt(),
            $entity->guestIds()->map(fn(GuestId $id) => $id->value()),
        );
    }

    public function createCollection(array $orders): array
    {
        return array_map(fn(Order $order) => $this->createFromEntity($order), $orders);
    }
}
