<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Order;

use Carbon\CarbonImmutable;
use Module\Booking\Shared\Domain\Booking\ValueObject\Context;
use Module\Booking\Shared\Domain\Order\Event\ClientChanged;
use Module\Booking\Shared\Domain\Order\ValueObject\ClientId;
use Module\Booking\Shared\Domain\Order\ValueObject\LegalId;
use Module\Booking\Shared\Domain\Order\ValueObject\OrderId;
use Module\Booking\Shared\Domain\Shared\ValueObject\GuestIdCollection;
use Module\Shared\Contracts\Domain\EntityInterface;
use Module\Shared\Enum\Booking\OrderStatusEnum;
use Module\Shared\Enum\CurrencyEnum;
use Sdk\Module\Foundation\Domain\Entity\AbstractAggregateRoot;

final class Order extends AbstractAggregateRoot implements EntityInterface
{
    public function __construct(
        private readonly OrderId $id,
        private CurrencyEnum $currency,
        private ClientId $clientId,
        private ?LegalId $legalId,
        private OrderStatusEnum $status,
        private readonly CarbonImmutable $createdAt,
        private readonly GuestIdCollection $guestIds,
        private readonly Context $context
    ) {}

    public function generateInvoice(): void
    {
        //@todo валидация - все брони имеют статус "Подтверждена"

        //@todo после генерации все брони переходят в статус "Выставлен счет"
    }

    public function id(): OrderId
    {
        return $this->id;
    }

    public function clientId(): ClientId
    {
        return $this->clientId;
    }

    public function status(): OrderStatusEnum
    {
        return $this->status;
    }

    public function setClientId(ClientId $clientId): void
    {
        $this->clientId = $clientId;
        $this->pushEvent(new ClientChanged($this));
    }

    public function setCurrency(CurrencyEnum $currency): void
    {
        $this->currency = $currency;
    }

    public function legalId(): ?LegalId
    {
        return $this->legalId;
    }

    public function setLegalId(?LegalId $legalId): void
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

    public function guestIds(): GuestIdCollection
    {
        return $this->guestIds;
    }

    public function context(): Context
    {
        return $this->context;
    }
}