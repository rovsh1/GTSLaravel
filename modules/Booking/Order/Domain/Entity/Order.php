<?php

declare(strict_types=1);

namespace Module\Booking\Order\Domain\Entity;

use Carbon\CarbonImmutable;
use Module\Booking\Order\Domain\Event\ClientChanged;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\Id;
use Module\Shared\Enum\CurrencyEnum;
use Sdk\Module\Foundation\Domain\Entity\AbstractAggregateRoot;

final class Order extends AbstractAggregateRoot implements EntityInterface
{
    public function __construct(
        private readonly Id $id,
        private CurrencyEnum $currency,
        private Id $clientId,
        private ?Id $legalId,
        private readonly CarbonImmutable $createdAt,
    ) {}

    public function generateInvoice(): void
    {
        //@todo валидация - все брони имеют статус "Подтверждена"

        //@todo после генерации все брони переходят в статус "Выставлен счет"
    }

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

    public function setCurrency(CurrencyEnum $currency): void
    {
        $this->currency = $currency;
    }

    public function legalId(): ?Id
    {
        return $this->legalId;
    }

    public function setLegalId(?Id $legalId): void
    {
        $this->legalId = $legalId;
    }

    public function currency(): CurrencyEnum
    {
        return $this->currency;
    }

    public function createdAt(): CarbonImmutable
    {
        return $this->createdAt;
    }
}
