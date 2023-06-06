<?php

declare(strict_types=1);

namespace Module\Booking\Common\Domain\Entity;

use Carbon\CarbonImmutable;
use Module\Booking\Common\Domain\ValueObject\RequestTypeEnum;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\Id;
use Sdk\Module\Foundation\Domain\Entity\AbstractAggregateRoot;

class Request extends AbstractAggregateRoot implements EntityInterface
{
    //@todo нужен статус вместо флага + кнопка сформировать запрос, после этого появляются 2 кнопки: отправить в отель и переформировать
    public function __construct(
        private readonly Id $id,
        private readonly int $bookingId,
        private readonly RequestTypeEnum $type,
        private readonly CarbonImmutable $dateCreate,
    ) {}

    public function id(): Id
    {
        return $this->id;
    }

    public function bookingId(): int
    {
        return $this->bookingId;
    }

    public function type(): RequestTypeEnum
    {
        return $this->type;
    }

    public function dateCreate(): CarbonImmutable
    {
        return $this->dateCreate;
    }
}
