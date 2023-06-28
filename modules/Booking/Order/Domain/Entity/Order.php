<?php

declare(strict_types=1);

namespace Module\Booking\Order\Domain\Entity;

use Module\Booking\Order\Domain\Event\ClientChanged;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\Id;
use Sdk\Module\Foundation\Domain\Entity\AbstractAggregateRoot;

final class Order extends AbstractAggregateRoot implements EntityInterface
{
    public function __construct(
        private readonly Id $id,
        private Id $currencyId,
        private Id $clientId,
        private readonly ?Id $legalId,
    ) {}

    public function id(): Id
    {
        return $this->id;
    }

    public function clientId(): Id
    {
        return $this->clientId;
    }

    public function setClientId(Id $clientId): void
    {
        $this->clientId = $clientId;
        $this->pushEvent(new ClientChanged($this));
    }

    public function legalId(): ?Id
    {
        return $this->legalId;
    }

    public function currencyId(): Id
    {
        return $this->currencyId;
    }
}
