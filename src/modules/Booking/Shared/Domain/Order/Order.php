<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Order;

use Carbon\CarbonImmutable;
use Module\Booking\Shared\Domain\Order\Event\ClientChanged;
use Module\Booking\Shared\Domain\Order\Support\Concerns\HasStatusesTrait;
use Module\Shared\Contracts\Domain\EntityInterface;
use Sdk\Booking\ValueObject\ClientId;
use Sdk\Booking\ValueObject\Context;
use Sdk\Booking\ValueObject\GuestIdCollection;
use Sdk\Booking\ValueObject\LegalId;
use Sdk\Booking\ValueObject\OrderId;
use Sdk\Booking\ValueObject\OrderPeriod;
use Sdk\Module\Foundation\Domain\Entity\AbstractAggregateRoot;
use Sdk\Shared\Enum\CurrencyEnum;
use Sdk\Shared\Enum\Order\OrderStatusEnum;
use Sdk\Shared\ValueObject\Money;

final class Order extends AbstractAggregateRoot implements EntityInterface
{
    use HasStatusesTrait;

    public function __construct(
        private readonly OrderId $id,
        private CurrencyEnum $currency,
        private ClientId $clientId,
        private ?LegalId $legalId,
        private OrderStatusEnum $status,
        private OrderPeriod $period,
        private readonly CarbonImmutable $createdAt,
        private readonly GuestIdCollection $guestIds,
        private readonly Money $clientPrice,
        private readonly Context $context
    ) {
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

    public function period(): OrderPeriod
    {
        return $this->period;
    }

    public function setPeriod(OrderPeriod $period): void
    {
        $this->period = $period;
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

    public function clientPrice(): Money
    {
        return $this->clientPrice;
    }
}
