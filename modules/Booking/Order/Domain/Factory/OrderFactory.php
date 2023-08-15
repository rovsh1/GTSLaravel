<?php

declare(strict_types=1);

namespace Module\Booking\Order\Domain\Factory;

use Carbon\CarbonImmutable;
use Module\Booking\Common\Domain\ValueObject\OrderId;
use Module\Booking\Order\Domain\Entity\Order;
use Module\Booking\Order\Domain\ValueObject\ClientId;
use Module\Booking\Order\Domain\ValueObject\LegalId;
use Module\Shared\Enum\CurrencyEnum;
use Sdk\Module\Foundation\Support\EntityFactory\AbstractEntityFactory;

class OrderFactory extends AbstractEntityFactory
{
    protected string $entity = Order::class;

    protected function fromArray(array $data): mixed
    {
        $legalId = $data['legal_id'] ?? null;

        return new $this->entity(
            new OrderId($data['id']),
            CurrencyEnum::fromId($data['currency_id']),
            new ClientId($data['client_id']),
            $legalId !== null ? new LegalId($legalId) : null,
            new CarbonImmutable($data['created_at'])
        );
    }
}
