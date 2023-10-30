<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Order\Factory;

use Carbon\CarbonImmutable;
use Module\Booking\Domain\Order\Order;
use Module\Booking\Domain\Order\ValueObject\ClientId;
use Module\Booking\Domain\Order\ValueObject\LegalId;
use Module\Booking\Domain\Order\ValueObject\OrderId;
use Module\Booking\Domain\Shared\ValueObject\GuestId;
use Module\Booking\Domain\Shared\ValueObject\GuestIdCollection;
use Module\Shared\Enum\CurrencyEnum;
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
            new CarbonImmutable($data['created_at']),
            new GuestIdCollection($guestIds)
        );
    }
}
