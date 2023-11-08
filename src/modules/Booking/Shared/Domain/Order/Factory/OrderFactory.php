<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Order\Factory;

use Carbon\CarbonImmutable;
use Module\Booking\Shared\Domain\Booking\ValueObject\Context;
use Module\Booking\Shared\Domain\Guest\ValueObject\GuestId;
use Module\Booking\Shared\Domain\Order\Order;
use Module\Booking\Shared\Domain\Order\ValueObject\ClientId;
use Module\Booking\Shared\Domain\Order\ValueObject\LegalId;
use Module\Booking\Shared\Domain\Order\ValueObject\OrderId;
use Module\Booking\Shared\Domain\Shared\ValueObject\CreatorId;
use Module\Booking\Shared\Domain\Shared\ValueObject\GuestIdCollection;
use Module\Shared\Enum\Booking\OrderStatusEnum;
use Module\Shared\Enum\CurrencyEnum;
use Module\Shared\Enum\SourceEnum;
use Sdk\Module\Foundation\Support\EntityFactory\AbstractEntityFactory;

class OrderFactory extends AbstractEntityFactory
{
    protected string $entity = Order::class;

    protected function fromArray(array $data): mixed
    {
        $legalId = $data['legal_id'] ?? null;
        $guestIds = array_map(fn(int $id) => new GuestId($id), $data['guest_ids'] ?? []);

        return new $this->entity(
            new OrderId($data['id']),
            CurrencyEnum::from($data['currency']),
            new ClientId($data['client_id']),
            $legalId !== null ? new LegalId($legalId) : null,
            OrderStatusEnum::from($data['status']),
            new CarbonImmutable($data['created_at']),
            new GuestIdCollection($guestIds),
            new Context(
                source: SourceEnum::from($data['source']),
                creatorId: new CreatorId($data['creator_id']),
            )
        );
    }
}
