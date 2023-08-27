<?php

declare(strict_types=1);

namespace Module\Booking\Order\Domain\Entity;

use Carbon\CarbonImmutable;
use Module\Booking\Common\Domain\ValueObject\OrderId;
use Module\Booking\Order\Domain\Event\ClientChanged;
use Module\Booking\Order\Domain\ValueObject\ClientId;
use Module\Booking\Order\Domain\ValueObject\LegalId;
use Module\Booking\Order\Domain\ValueObject\TouristIdsCollection;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Enum\CurrencyEnum;
use Sdk\Module\Foundation\Domain\Entity\AbstractAggregateRoot;

final class Order extends AbstractAggregateRoot implements EntityInterface
{
    public function __construct(
        private readonly OrderId $id,
        private CurrencyEnum $currency,
        private ClientId $clientId,
        private ?LegalId $legalId,
        private readonly CarbonImmutable $createdAt,
        private readonly TouristIdsCollection $touristIds,
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

    public function touristIds(): TouristIdsCollection
    {
        return $this->touristIds;
    }
}
