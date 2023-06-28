<?php

declare(strict_types=1);

namespace Module\Booking\Order\Domain\Factory;

use Module\Booking\Order\Domain\Entity\Order;
use Module\Shared\Domain\ValueObject\Id;
use Module\Shared\Enum\CurrencyEnum;
use Sdk\Module\Foundation\Support\EntityFactory\AbstractEntityFactory;

class OrderFactory extends AbstractEntityFactory
{
    protected string $entity = Order::class;

    protected function fromArray(array $data): mixed
    {
        $legalId = $data['legal_id'] ?? null;


        return new $this->entity(
            new Id($data['id']),
            CurrencyEnum::fromId($data['currency_id']),
            new Id($data['client_id']),
            $legalId !== null ? new Id($legalId) : null,
        );
    }
}
