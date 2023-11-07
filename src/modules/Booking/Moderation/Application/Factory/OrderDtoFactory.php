<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Factory;

use Module\Booking\Moderation\Application\Dto\OrderDto;
use Module\Booking\Shared\Domain\Guest\ValueObject\GuestId;
use Module\Booking\Shared\Domain\Order\Order;
use Module\Shared\Contracts\Service\TranslatorInterface;
use Module\Shared\Dto\CurrencyDto;

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
            $entity->context()->creatorId()->value(),
            $entity->context()->source()
        );
    }

    public function createCollection(array $orders): array
    {
        return array_map(fn(Order $order) => $this->createFromEntity($order), $orders);
    }
}
