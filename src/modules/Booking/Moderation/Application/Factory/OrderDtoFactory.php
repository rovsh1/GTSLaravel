<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Factory;

use Module\Booking\Moderation\Application\Dto\OrderDto;
use Module\Booking\Shared\Domain\Order\Order;
use Sdk\Booking\ValueObject\GuestId;
use Sdk\Shared\Contracts\Service\TranslatorInterface;
use Sdk\Shared\Dto\CurrencyDto;
use Sdk\Shared\Dto\MoneyDto;

class OrderDtoFactory
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly OrderStatusDtoFactory $statusDtoFactory
    ) {}

    public function createFromEntity(Order $entity): OrderDto
    {
        return new OrderDto(
            $entity->id()->value(),
            $this->statusDtoFactory->get($entity->status()),
            $entity->clientId()->value(),
            $entity->legalId()?->value(),
            $entity->createdAt(),
            $entity->guestIds()->map(fn(GuestId $id) => $id->value()),
            $entity->context()->creatorId()->value(),
            new MoneyDto(
                CurrencyDto::fromEnum($entity->clientPrice()->currency(), $this->translator),
                $entity->clientPrice()->value()
            ),
            $entity->context()->source()
        );
    }

    public function createCollection(array $orders): array
    {
        return array_map(fn(Order $order) => $this->createFromEntity($order), $orders);
    }
}
