<?php

declare(strict_types=1);

namespace Module\Pricing\Domain\Markup\Entity;

use Module\Pricing\Domain\Markup\ValueObject\ClientMarkupId;
use Module\Pricing\Domain\Markup\ValueObject\MarkupValue;
use Module\Shared\Domain\Entity\EntityInterface;
use Sdk\Module\Foundation\Domain\Entity\AbstractAggregateRoot;

class ClientMarkup extends AbstractAggregateRoot implements EntityInterface
{
    public function __construct(
        private readonly ClientMarkupId $id,
        private readonly ?int $hotelId,
        private readonly ?int $hotelRoomId,
        private MarkupValue $value
    ) {}

    public function id(): ClientMarkupId
    {
        return $this->id;
    }

    public function hotelId(): int
    {
        return $this->hotelId;
    }

    public function hotelRoomId(): int
    {
        return $this->hotelRoomId;
    }

    public function value(): MarkupValue
    {
        return $this->value;
    }

    public function setValue(MarkupValue $value): void
    {
        $this->value = $value;
    }
}
